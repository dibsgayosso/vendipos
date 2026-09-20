<?php
declare(strict_types=1);
namespace Vendi\Sales;
use PDO;use RuntimeException;
final class VoidSaleService{
 public function __construct(private PDO $db){}
 public function void(int $businessId,int $branchId,int $userId,int $saleId,string $reason):void{
  if(trim($reason)==='')throw new RuntimeException('El motivo es obligatorio.');$this->db->beginTransaction();
  try{$q=$this->db->prepare("SELECT status FROM sales WHERE id=? AND business_id=? AND branch_id=? FOR UPDATE");$q->execute([$saleId,$businessId,$branchId]);if($q->fetchColumn()!=='completed')throw new RuntimeException('La venta no puede cancelarse.');
   $i=$this->db->prepare("SELECT product_id,qty FROM sale_items WHERE sale_id=?");$i->execute([$saleId]);foreach($i->fetchAll() as $x){$u=$this->db->prepare("UPDATE inventory SET qty=qty+? WHERE business_id=? AND branch_id=? AND product_id=?");$u->execute([$x['qty'],$businessId,$branchId,$x['product_id']]);$m=$this->db->prepare("INSERT INTO stock_movements(business_id,branch_id,product_id,user_id,type,qty,reference_type,reference_id) VALUES(?,?,?,?,'return',?,'void_sale',?)");$m->execute([$businessId,$branchId,$x['product_id'],$userId,$x['qty'],$saleId]);}
   $u=$this->db->prepare("UPDATE sales SET status='cancelled' WHERE id=?");$u->execute([$saleId]);$a=$this->db->prepare("INSERT INTO audit_log(business_id,branch_id,user_id,event,entity_type,entity_id,metadata,created_at) VALUES(?,?,?,'sale.voided','sale',?,?,NOW())");$a->execute([$businessId,$branchId,$userId,$saleId,json_encode(['reason'=>trim($reason)],JSON_UNESCAPED_UNICODE)]);$this->db->commit();
  }catch(\Throwable $e){$this->db->rollBack();throw $e;}
 }
}