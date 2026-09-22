<?php
declare(strict_types=1);
namespace Vendi\Purchases;
use PDO;use RuntimeException;
final class PurchaseOrderService{
 public function __construct(private PDO $db){}
 public function create(int $businessId,int $branchId,int $supplierId,int $userId,array $items,?string $notes=null):int{
  if(!$items)throw new RuntimeException('La orden requiere productos.');$this->db->beginTransaction();
  try{$folio='OC-'.date('Ymd-His').'-'.random_int(100,999);$subtotal=0;$tax=0;foreach($items as $x){$line=(float)$x['quantity']*(float)$x['unit_cost'];$subtotal+=$line;$tax+=$line*((float)($x['tax_rate']??0)/100);}$total=$subtotal+$tax;
   $q=$this->db->prepare("INSERT INTO purchase_orders(business_id,branch_id,supplier_id,user_id,folio,notes,subtotal,tax,total) VALUES(?,?,?,?,?,?,?,?,?)");$q->execute([$businessId,$branchId,$supplierId,$userId,$folio,$notes,$subtotal,$tax,$total]);$id=(int)$this->db->lastInsertId();
   $i=$this->db->prepare("INSERT INTO purchase_order_items(purchase_order_id,product_id,presentation_id,description,qty_ordered,unit_cost,tax_rate,line_total) VALUES(?,?,?,?,?,?,?,?)");
   foreach($items as $x){$line=(float)$x['quantity']*(float)$x['unit_cost'];$i->execute([$id,(int)$x['product_id'],(int)$x['presentation_id'],(string)$x['description'],(float)$x['quantity'],(float)$x['unit_cost'],(float)($x['tax_rate']??0),$line]);}
   $this->db->commit();return$id;}catch(\Throwable $e){$this->db->rollBack();throw$e;}
 }
 public function detail(int $businessId,int $id):array{$q=$this->db->prepare("SELECT po.*,s.name supplier_name FROM purchase_orders po JOIN suppliers s ON s.id=po.supplier_id WHERE po.id=? AND po.business_id=?");$q->execute([$id,$businessId]);$po=$q->fetch();if(!$po)throw new RuntimeException('Orden no encontrada.');$i=$this->db->prepare("SELECT * FROM purchase_order_items WHERE purchase_order_id=? ORDER BY id");$i->execute([$id]);$po['items']=$i->fetchAll();return$po;}
}