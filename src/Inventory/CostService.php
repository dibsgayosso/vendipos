<?php
declare(strict_types=1);
namespace Vendi\Inventory;
use PDO;use RuntimeException;
final class CostService{
 public function __construct(private PDO $db){}
 public function current(int $businessId,int $productId,int $presentationId):array{
  $q=$this->db->prepare("SELECT pp.purchase_cost,pp.conversion_to_base FROM product_presentations pp WHERE pp.id=? AND pp.product_id=? AND pp.business_id=? AND pp.active=1 LIMIT 1");
  $q->execute([$presentationId,$productId,$businessId]);$r=$q->fetch();if(!$r)throw new RuntimeException('Presentación no encontrada.');
  $factor=(float)$r['conversion_to_base'];$presentationCost=$r['purchase_cost']===null?null:(float)$r['purchase_cost'];
  return ['presentation_cost'=>$presentationCost,'base_cost'=>$presentationCost===null?null:round($presentationCost/$factor,4),'conversion_to_base'=>$factor];
 }
 public function updateFromReceiving(int $businessId,int $branchId,int $userId,int $productId,int $presentationId,float $newPresentationCost,?int $referenceId=null):array{
  if($newPresentationCost<0)throw new RuntimeException('Costo inválido.');
  $q=$this->db->prepare("SELECT purchase_cost,conversion_to_base FROM product_presentations WHERE id=? AND product_id=? AND business_id=? FOR UPDATE");$q->execute([$presentationId,$productId,$businessId]);$r=$q->fetch();if(!$r)throw new RuntimeException('Presentación no encontrada.');
  $old=(float)($r['purchase_cost']??0);$factor=(float)$r['conversion_to_base'];
  $u=$this->db->prepare("UPDATE product_presentations SET purchase_cost=? WHERE id=? AND business_id=?");$u->execute([$newPresentationCost,$presentationId,$businessId]);
  $baseCost=round($newPresentationCost/$factor,4);
  $p=$this->db->prepare("UPDATE products SET cost_price=? WHERE id=? AND business_id=?");$p->execute([$baseCost,$productId,$businessId]);
  $h=$this->db->prepare("INSERT INTO product_cost_history(business_id,branch_id,product_id,presentation_id,old_cost,new_cost,source,reference_id,user_id) VALUES(?,?,?,?,?,?,'receiving',?,?)");$h->execute([$businessId,$branchId,$productId,$presentationId,$old,$newPresentationCost,$referenceId,$userId]);
  return ['old_cost'=>$old,'new_cost'=>$newPresentationCost,'base_cost'=>$baseCost];
 }
}