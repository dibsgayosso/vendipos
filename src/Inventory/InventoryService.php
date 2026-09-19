<?php
declare(strict_types=1);
namespace Vendi\Inventory;
use PDO;
use RuntimeException;
final class InventoryService {
 public function __construct(private PDO $db){}
 public function adjust(int $businessId,int $branchId,int $productId,float $qty,string $reason,int $userId): void {
  $this->db->beginTransaction();
  try {
   $q=$this->db->prepare("SELECT qty FROM inventory WHERE business_id=? AND branch_id=? AND product_id=? FOR UPDATE");
   $q->execute([$businessId,$branchId,$productId]); $row=$q->fetch();
   if(!$row) throw new RuntimeException('Inventario no encontrado.');
   $new=(float)$row['qty']+$qty; if($new<0) throw new RuntimeException('Inventario insuficiente.');
   $u=$this->db->prepare("UPDATE inventory SET qty=? WHERE business_id=? AND branch_id=? AND product_id=?");
   $u->execute([$new,$businessId,$branchId,$productId]);
   $a=$this->db->prepare("INSERT INTO audit_log(business_id,branch_id,user_id,event,entity_type,entity_id,metadata) VALUES(?,?,?,?,?,?,?)");
   $a->execute([$businessId,$branchId,$userId,'inventory.adjust','product',$productId,json_encode(['delta'=>$qty,'new_qty'=>$new,'reason'=>$reason],JSON_THROW_ON_ERROR)]);
   $this->db->commit();
  } catch(\Throwable $e){$this->db->rollBack();throw $e;}
 }
}