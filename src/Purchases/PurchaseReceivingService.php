<?php
declare(strict_types=1);
namespace Vendi\Purchases;
use PDO;use RuntimeException;use Vendi\Inventory\ReceivingService;
final class PurchaseReceivingService{
 public function __construct(private PDO $db,private ReceivingService $receiving){}
 public function receive(int $businessId,int $branchId,int $userId,int $orderId,array $lines,?string $supplierDocument=null):int{
  $q=$this->db->prepare("SELECT * FROM purchase_orders WHERE id=? AND business_id=? AND branch_id=? FOR UPDATE");$this->db->beginTransaction();
  try{$q->execute([$orderId,$businessId,$branchId]);$po=$q->fetch();if(!$po||in_array($po['status'],['received','cancelled'],true))throw new RuntimeException('Orden no disponible para recepción.');
   $r=$this->db->prepare("INSERT INTO purchase_receipts(business_id,branch_id,purchase_order_id,supplier_id,user_id,supplier_document) VALUES(?,?,?,?,?,?)");$r->execute([$businessId,$branchId,$orderId,(int)$po['supplier_id'],$userId,$supplierDocument]);$receiptId=(int)$this->db->lastInsertId();
   foreach($lines as $x){$it=$this->db->prepare("SELECT * FROM purchase_order_items WHERE id=? AND purchase_order_id=? FOR UPDATE");$it->execute([(int)$x['purchase_order_item_id'],$orderId]);$item=$it->fetch();if(!$item)throw new RuntimeException('Partida inválida.');$qty=(float)$x['quantity'];$remaining=(float)$item['qty_ordered']-(float)$item['qty_received'];if($qty<=0||$qty>$remaining)throw new RuntimeException('Cantidad recibida excede lo pendiente.');
    $this->receiving->receive($businessId,$branchId,$userId,['product_id'=>(int)$item['product_id'],'presentation_id'=>(int)$item['presentation_id'],'quantity'=>$qty,'lot_number'=>$x['lot_number']??null,'expires_at'=>$x['expires_at']??null,'manufactured_at'=>$x['manufactured_at']??null,'serials'=>$x['serials']??[],'presentation_cost'=>(float)($x['presentation_cost']??$item['unit_cost']),'update_cost'=>(bool)($x['update_cost']??false)],$receiptId);
    $this->db->prepare("UPDATE purchase_order_items SET qty_received=qty_received+? WHERE id=?")->execute([$qty,(int)$item['id']]);$this->db->prepare("INSERT INTO purchase_receipt_items(receipt_id,purchase_order_item_id,product_id,presentation_id,qty_received,presentation_cost,lot_number,expires_at) VALUES(?,?,?,?,?,?,?,?)")->execute([$receiptId,(int)$item['id'],(int)$item['product_id'],(int)$item['presentation_id'],$qty,(float)($x['presentation_cost']??$item['unit_cost']),$x['lot_number']??null,$x['expires_at']??null]);}
   $c=$this->db->prepare("SELECT COUNT(*) FROM purchase_order_items WHERE purchase_order_id=? AND qty_received<qty_ordered");$c->execute([$orderId]);$status=(int)$c->fetchColumn()===0?'received':'partial';$this->db->prepare("UPDATE purchase_orders SET status=? WHERE id=?")->execute([$status,$orderId]);$this->db->commit();return$receiptId;
  }catch(\Throwable $e){if($this->db->inTransaction())$this->db->rollBack();throw$e;}
 }
}