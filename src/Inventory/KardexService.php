<?php
declare(strict_types=1);
namespace Vendi\Inventory;
use PDO;
final class KardexService{
 public function __construct(private PDO $db){}
 public function product(int $businessId,int $productId,?int $branchId=null,int $limit=200):array{
  $sql="SELECT sm.id,sm.created_at,sm.branch_id,b.name branch_name,sm.type,sm.quantity,sm.reference_type,sm.reference_id,sm.user_id,u.name user_name,sm.variant_id,pv.name variant_name FROM stock_movements sm JOIN branches b ON b.id=sm.branch_id LEFT JOIN users u ON u.id=sm.user_id LEFT JOIN product_variants pv ON pv.id=sm.variant_id WHERE sm.business_id=? AND sm.product_id=?";
  $args=[$businessId,$productId];if($branchId){$sql.=" AND sm.branch_id=?";$args[]=$branchId;}$sql.=" ORDER BY sm.id DESC LIMIT ".max(1,min(500,$limit));$q=$this->db->prepare($sql);$q->execute($args);return$q->fetchAll();
 }
 public function stock(int $businessId,int $productId):array{$q=$this->db->prepare("SELECT i.branch_id,b.name branch_name,i.quantity FROM inventory i JOIN branches b ON b.id=i.branch_id WHERE i.product_id=? AND b.business_id=? ORDER BY b.name");$q->execute([$productId,$businessId]);return$q->fetchAll();}
 public function expiring(int $businessId,int $days=60):array{$q=$this->db->prepare("SELECT l.*,p.name product_name,b.name branch_name,DATEDIFF(l.expires_at,CURDATE()) days_left FROM inventory_lots l JOIN products p ON p.id=l.product_id JOIN branches b ON b.id=l.branch_id WHERE l.business_id=? AND l.qty_base>0 AND l.expires_at IS NOT NULL AND l.expires_at<=DATE_ADD(CURDATE(),INTERVAL ? DAY) ORDER BY l.expires_at,l.product_id");$q->execute([$businessId,$days]);return$q->fetchAll();}
}