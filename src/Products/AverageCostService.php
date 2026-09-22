<?php
declare(strict_types=1);
namespace Vendi\Products;
use PDO;use RuntimeException;
final class AverageCostService{
 public function __construct(private PDO $db){}
 public function recalculate(int $businessId,int $branchId,int $productId):float{
  $q=$this->db->prepare("SELECT COALESCE(SUM(qty_received*unit_cost)/NULLIF(SUM(qty_received),0),0) avg_cost FROM product_cost_layers WHERE business_id=? AND branch_id=? AND product_id=? AND qty_received>0");
  $q->execute([$businessId,$branchId,$productId]);$avg=round((float)$q->fetchColumn(),4);
  $u=$this->db->prepare("UPDATE products SET average_cost=? WHERE business_id=? AND id=?");$u->execute([$avg,$businessId,$productId]);return $avg;
 }
 public function record(int $businessId,int $branchId,int $productId,?int $variantId,?int $lotId,string $source,?int $referenceId,float $qty,float $unitCost):float{
  if($qty<=0||$unitCost<0)throw new RuntimeException('Cantidad o costo inválido.');
  $q=$this->db->prepare("INSERT INTO product_cost_layers(business_id,branch_id,product_id,variant_id,lot_id,source,reference_id,qty_received,unit_cost) VALUES(?,?,?,?,?,?,?,?,?)");
  $q->execute([$businessId,$branchId,$productId,$variantId,$lotId,$source,$referenceId,$qty,$unitCost]);return $this->recalculate($businessId,$branchId,$productId);
 }
}