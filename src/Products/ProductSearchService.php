<?php
declare(strict_types=1);
namespace Vendi\Products;
use PDO;
final class ProductSearchService {
 public function __construct(private PDO $db){}
 public function search(int $businessId,int $branchId,string $term,int $limit=12): array {
  $term=trim($term); if(mb_strlen($term)<3) return [];
  $limit=max(1,min($limit,30));
  $sql="SELECT p.id,p.sku,p.barcode,p.name,p.unit,p.price,
    COALESCE(i.qty,0) stock,
    COALESCE(SUM(CASE WHEN s.status='completed' AND s.completed_at>=DATE_SUB(NOW(),INTERVAL 90 DAY) THEN si.qty ELSE 0 END),0) sold_90d,
    CASE WHEN LOWER(p.name) LIKE LOWER(?) THEN 0
         WHEN LOWER(p.sku) LIKE LOWER(?) THEN 1
         WHEN LOWER(COALESCE(p.barcode,'')) LIKE LOWER(?) THEN 2
         WHEN LOWER(p.name) LIKE LOWER(?) THEN 3 ELSE 4 END relevance
   FROM products p
   LEFT JOIN inventory i ON i.product_id=p.id AND i.business_id=p.business_id AND i.branch_id=?
   LEFT JOIN sale_items si ON si.product_id=p.id
   LEFT JOIN sales s ON s.id=si.sale_id AND s.business_id=p.business_id AND s.branch_id=?
   WHERE p.business_id=? AND p.active=1
     AND (LOWER(p.name) LIKE LOWER(?) OR LOWER(p.sku) LIKE LOWER(?) OR LOWER(COALESCE(p.barcode,'')) LIKE LOWER(?))
   GROUP BY p.id,p.sku,p.barcode,p.name,p.unit,p.price,i.qty
   ORDER BY relevance ASC,sold_90d DESC,p.name ASC LIMIT ".$limit;
  $prefix=$term.'%';$contains='%'.$term.'%';
  $q=$this->db->prepare($sql);
  $q->execute([$prefix,$prefix,$prefix,$contains,$branchId,$branchId,$businessId,$contains,$contains,$contains]);
  return $q->fetchAll();
 }
}