<?php
declare(strict_types=1);
namespace Vendi\Inventory;
use PDO;use RuntimeException;
final class ReceivingService{
 public function __construct(private PDO $db,private PresentationService $presentations,private LotService $lots,private SerialService $serials,private ?CostService $costs=null){}
 public function receive(int $businessId,int $branchId,int $userId,array $line,?int $purchaseId=null):array{
  $productId=(int)($line['product_id']??0);$presentationId=(int)($line['presentation_id']??0);$qty=(float)($line['quantity']??0);
  if(!$productId||!$presentationId||$qty<=0)throw new RuntimeException('Producto, presentación y cantidad son obligatorios.');
  $q=$this->db->prepare("SELECT tracks_lots,tracks_expiration,tracks_serials FROM products WHERE id=? AND business_id=? LIMIT 1");$q->execute([$productId,$businessId]);$p=$q->fetch();if(!$p)throw new RuntimeException('Producto no encontrado.');
  $qtyBase=$this->presentations->toBase($businessId,$presentationId,$qty);$lotId=null;$costChange=null;$serialList=$line['serials']??[];
  if((int)$p['tracks_serials']===1 && count($serialList)!==(int)round($qtyBase))throw new RuntimeException('Debe capturar una serie por cada unidad base recibida.');
  if((int)$p['tracks_lots']===1){$lot=trim((string)($line['lot_number']??''));if($lot==='')throw new RuntimeException('Este producto requiere lote.');if((int)$p['tracks_expiration']===1 && empty($line['expires_at']))throw new RuntimeException('Este producto requiere caducidad.');}
  $this->db->beginTransaction();
  try{
   if((int)$p['tracks_lots']===1)$lotId=$this->lots->receive($businessId,$branchId,$productId,(string)$line['lot_number'],$qtyBase,$line['expires_at']??null,$line['manufactured_at']??null,isset($line['cost_per_base'])?(float)$line['cost_per_base']:null);
   $inv=$this->db->prepare("INSERT INTO inventory(branch_id,product_id,quantity) VALUES(?,?,?) ON DUPLICATE KEY UPDATE quantity=quantity+VALUES(quantity)");$inv->execute([$branchId,$productId,$qtyBase]);
   $m=$this->db->prepare("INSERT INTO stock_movements(business_id,branch_id,product_id,type,quantity,reference_type,reference_id,user_id,created_at) VALUES(?,?,?,'purchase',?,'purchase',?,?,NOW())");$m->execute([$businessId,$branchId,$productId,$qtyBase,$purchaseId,$userId]);
   foreach($serialList as $serial)$this->serials->receive($businessId,$branchId,$productId,(string)$serial,$lotId,$purchaseId);if(($line['update_cost']??false) && isset($line['presentation_cost']) && $this->costs)$costChange=$this->costs->updateFromReceiving($businessId,$branchId,$userId,$productId,$presentationId,(float)$line['presentation_cost'],$purchaseId);
   $this->db->commit();return ['qty_base'=>$qtyBase,'lot_id'=>$lotId,'serials'=>count($serialList),'cost_change'=>$costChange];
  }catch(\Throwable $e){$this->db->rollBack();throw $e;}
 }
}