<?php
declare(strict_types=1);
namespace Vendi\Sales;
use PDO;
use RuntimeException;
use Vendi\Support\Money;
final class SaleService {
 public function __construct(private PDO $db){}
 public function checkout(int $businessId,int $branchId,int $userId,?int $customerId,array $items,array $payments,string $idempotencyKey): int {
  if(!$items) throw new RuntimeException('La venta está vacía.');
  $this->db->beginTransaction();
  try {
   $dup=$this->db->prepare("SELECT id FROM sales WHERE business_id=? AND idempotency_key=? LIMIT 1");
   $dup->execute([$businessId,$idempotencyKey]); if($id=$dup->fetchColumn()){ $this->db->commit(); return (int)$id; }
   $subtotal=$discount=$tax=$total=$costTotal=0.0; $normalized=[];
   foreach($items as $i){
    $p=$this->db->prepare("SELECT id,name,price,cost,tax_rate FROM products WHERE id=? AND business_id=? AND active=1 FOR UPDATE");
    $p->execute([(int)$i['product_id'],$businessId]); $product=$p->fetch(); if(!$product) throw new RuntimeException('Producto inválido.');
    $qty=(float)$i['qty']; $discountPct=(float)($i['discount_percent']??0);
    $line=Money::calculateLine($qty,(float)$product['price'],$discountPct,(float)$product['tax_rate']);
    $stock=$this->db->prepare("SELECT qty FROM inventory WHERE business_id=? AND branch_id=? AND product_id=? FOR UPDATE");
    $stock->execute([$businessId,$branchId,$product['id']]); $available=$stock->fetchColumn();
    if($available===false||(float)$available<$qty) throw new RuntimeException('Inventario insuficiente: '.$product['name']);
    $normalized[]=[$product,$qty,$line];
    $subtotal+=$line['gross'];$discount+=$line['discount'];$tax+=$line['tax'];$total+=$line['total'];$costTotal+=(float)$product['cost']*$qty;
   }
   $payments=PaymentValidator::validate($payments,Money::round($total));
   $cash=$this->db->prepare("SELECT id FROM cash_sessions WHERE business_id=? AND branch_id=? AND user_id=? AND status='open' ORDER BY id DESC LIMIT 1 FOR UPDATE");$cash->execute([$businessId,$branchId,$userId]);$cashSessionId=$cash->fetchColumn();if(!$cashSessionId)throw new RuntimeException('Debes abrir caja antes de cobrar.');
   $paid=array_sum(array_map(fn($p)=>(float)$p['amount'],$payments));
   if(Money::round($paid)<Money::round($total)) throw new RuntimeException('Pago insuficiente.');
   $s=$this->db->prepare("INSERT INTO sales(business_id,branch_id,user_id,customer_id,status,subtotal,discount,tax,total,cost_total,idempotency_key,completed_at) VALUES(?,?,?,?,'completed',?,?,?,?,?,?,NOW())");
   $s->execute([$businessId,$branchId,$userId,$customerId,Money::round($subtotal),Money::round($discount),Money::round($tax),Money::round($total),Money::round($costTotal),$idempotencyKey]); $saleId=(int)$this->db->lastInsertId();
   foreach($normalized as [$product,$qty,$line]){
    $si=$this->db->prepare("INSERT INTO sale_items(sale_id,product_id,description,qty,unit_price,unit_cost,discount,tax,line_total) VALUES(?,?,?,?,?,?,?,?,?)");
    $si->execute([$saleId,$product['id'],$product['name'],$qty,$product['price'],$product['cost'],$line['discount'],$line['tax'],$line['total']]);
    $st=$this->db->prepare("UPDATE inventory SET qty=qty-? WHERE business_id=? AND branch_id=? AND product_id=?");
    $st->execute([$qty,$businessId,$branchId,$product['id']]);
    $mv=$this->db->prepare("INSERT INTO stock_movements(business_id,branch_id,product_id,user_id,type,qty,unit_cost,reference_type,reference_id) VALUES(?,?,?,?,'sale',?,?,?,?)");$mv->execute([$businessId,$branchId,$product['id'],$userId,-$qty,$product['cost'],'sale',$saleId]);
   }
   foreach($payments as $pay){
    if((float)$pay['amount']<=0) continue;
    $q=$this->db->prepare("INSERT INTO payments(sale_id,cash_session_id,method,amount,reference) VALUES(?,?,?,?,?)");
    $q->execute([$saleId,$cashSessionId,$pay['method'],Money::round((float)$pay['amount']),$pay['reference']??null]);
    if($pay['method']==='cash'){$cm=$this->db->prepare("INSERT INTO cash_movements(business_id,branch_id,cash_session_id,user_id,type,amount,reference_type,reference_id) VALUES(?,?,?,?,'sale',?,'sale',?)");$cm->execute([$businessId,$branchId,$cashSessionId,$userId,Money::round((float)$pay['amount']),$saleId]);}
   }
   $this->db->commit(); return $saleId;
  } catch(\Throwable $e){$this->db->rollBack();throw $e;}
 }
}