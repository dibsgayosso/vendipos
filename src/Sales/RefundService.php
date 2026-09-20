<?php
declare(strict_types=1);
namespace Vendi\Sales;
use PDO;use RuntimeException;
final class RefundService{
 public function __construct(private PDO $db){}
 public function refund(int $businessId,int $branchId,int $userId,int $saleId,array $requested,string $reason,array $refundPayments=[]):int{
  if(trim($reason)==='')throw new RuntimeException('El motivo es obligatorio.');
  if(!$requested)throw new RuntimeException('Selecciona artículos a devolver.');
  $this->db->beginTransaction();
  try{
   $s=$this->db->prepare("SELECT id,status FROM sales WHERE id=? AND business_id=? AND branch_id=? FOR UPDATE");$s->execute([$saleId,$businessId,$branchId]);$sale=$s->fetch();if(!$sale||$sale['status']!=='completed')throw new RuntimeException('La venta no admite devolución.');
   $total=0.0;$lines=[];
   foreach($requested as $r){$q=$this->db->prepare("SELECT si.id,si.product_id,si.qty,si.line_total,COALESCE((SELECT SUM(ri.qty) FROM refund_items ri JOIN refunds rf ON rf.id=ri.refund_id WHERE ri.sale_item_id=si.id AND rf.status='completed'),0) returned FROM sale_items si WHERE si.id=? AND si.sale_id=? FOR UPDATE");$q->execute([(int)$r['sale_item_id'],$saleId]);$i=$q->fetch();if(!$i)throw new RuntimeException('Partida inválida.');$qty=(float)$r['qty'];$available=(float)$i['qty']-(float)$i['returned'];if($qty<=0||$qty>$available)throw new RuntimeException('Cantidad de devolución inválida.');$amount=round(((float)$i['line_total']/(float)$i['qty'])*$qty,2);$total+=$amount;$lines[]=[$i,$qty,$amount];}
   $r=$this->db->prepare("INSERT INTO refunds(business_id,branch_id,sale_id,user_id,reason,total) VALUES(?,?,?,?,?,?)");$r->execute([$businessId,$branchId,$saleId,$userId,trim($reason),round($total,2)]);$refundId=(int)$this->db->lastInsertId();
   foreach($lines as [$i,$qty,$amount]){$x=$this->db->prepare("INSERT INTO refund_items(refund_id,sale_item_id,product_id,qty,amount) VALUES(?,?,?,?,?)");$x->execute([$refundId,$i['id'],$i['product_id'],$qty,$amount]);$st=$this->db->prepare("UPDATE inventory SET qty=qty+? WHERE business_id=? AND branch_id=? AND product_id=?");$st->execute([$qty,$businessId,$branchId,$i['product_id']]);$mv=$this->db->prepare("INSERT INTO stock_movements(business_id,branch_id,product_id,user_id,type,qty,reference_type,reference_id) VALUES(?,?,?,?,'return',?,'refund',?)");$mv->execute([$businessId,$branchId,$i['product_id'],$userId,$qty,$refundId]);}
   $refundPaid=0.0;foreach($refundPayments as $p){$amount=(float)($p['amount']??0);if($amount<=0)continue;$refundPaid+=$amount;$x=$this->db->prepare("INSERT INTO refund_payments(refund_id,payment_method_id,method,amount,reference) VALUES(?,?,?,?,?)");$x->execute([$refundId,$p['payment_method_id']??null,$p['method'],$amount,$p['reference']??null]);}
   if($refundPayments&&round($refundPaid,2)!==round($total,2))throw new RuntimeException('El reembolso debe coincidir con el total devuelto.');
   foreach($refundPayments as $p){if(($p['method']??'')==='cash'&&(float)($p['amount']??0)>0){$cs=$this->db->prepare("SELECT id FROM cash_sessions WHERE business_id=? AND branch_id=? AND user_id=? AND status='open' ORDER BY id DESC LIMIT 1 FOR UPDATE");$cs->execute([$businessId,$branchId,$userId]);$sid=$cs->fetchColumn();if(!$sid)throw new RuntimeException('Abre caja para devolver efectivo.');$cm=$this->db->prepare("INSERT INTO cash_movements(business_id,branch_id,cash_session_id,user_id,type,amount,reference_type,reference_id,note) VALUES(?,?,?,?,'refund',?,'refund',?,?)");$cm->execute([$businessId,$branchId,$sid,$userId,(float)$p['amount'],$refundId,trim($reason)]);}}
   $a=$this->db->prepare("INSERT INTO audit_log(business_id,branch_id,user_id,event,entity_type,entity_id,metadata,created_at) VALUES(?,?,?,'sale.refunded','refund',?,?,NOW())");$a->execute([$businessId,$branchId,$userId,$refundId,json_encode(['sale_id'=>$saleId,'total'=>round($total,2),'reason'=>trim($reason)],JSON_UNESCAPED_UNICODE)]);
   $this->db->commit();return $refundId;
  }catch(\Throwable $e){$this->db->rollBack();throw $e;}
 }
}