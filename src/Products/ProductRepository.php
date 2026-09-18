<?php
declare(strict_types=1);
namespace Vendi\Products;
use PDO;
final class ProductRepository{
 public function __construct(private PDO $db){}
 public function findSellable(int $businessId,int $branchId,int $id):?array{
  $q=$this->db->prepare("SELECT p.id,p.sku,p.barcode,p.name,p.unit,p.price,p.cost,p.tax_rate,COALESCE(i.qty,0) stock FROM products p LEFT JOIN inventory i ON i.product_id=p.id AND i.business_id=p.business_id AND i.branch_id=? WHERE p.business_id=? AND p.id=? AND p.active=1 LIMIT 1");
  $q->execute([$branchId,$businessId,$id]);$r=$q->fetch();return $r?:null;
 }
 public function barcode(int $businessId,int $branchId,string $code):?array{
  $q=$this->db->prepare("SELECT p.id,p.sku,p.barcode,p.name,p.unit,p.price,p.tax_rate,COALESCE(i.qty,0) stock FROM products p LEFT JOIN inventory i ON i.product_id=p.id AND i.business_id=p.business_id AND i.branch_id=? WHERE p.business_id=? AND p.barcode=? AND p.active=1 LIMIT 1");
  $q->execute([$branchId,$businessId,trim($code)]);$r=$q->fetch();return $r?:null;
 }
}