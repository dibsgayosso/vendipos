<?php
declare(strict_types=1);
namespace Vendi\Sales;
use PDO;use RuntimeException;
final class SalesHistoryService{
 public function __construct(private PDO $db){}
 public function search(int $businessId,int $branchId,string $term='',int $limit=50):array{
  $limit=max(1,min($limit,100));$like='%'.trim($term).'%';
  $q=$this->db->prepare("SELECT s.id,s.completed_at,s.status,s.total,u.name cashier,COALESCE(c.name,'Público general') customer FROM sales s JOIN users u ON u.id=s.user_id LEFT JOIN customers c ON c.id=s.customer_id WHERE s.business_id=? AND s.branch_id=? AND (CAST(s.id AS CHAR) LIKE ? OR COALESCE(c.name,'') LIKE ?) ORDER BY s.id DESC LIMIT ".$limit);
  $q->execute([$businessId,$branchId,$like,$like]);return $q->fetchAll();
 }
 public function detail(int $businessId,int $branchId,int $saleId):array{
  $q=$this->db->prepare("SELECT s.*,u.name cashier,COALESCE(c.name,'Público general') customer FROM sales s JOIN users u ON u.id=s.user_id LEFT JOIN customers c ON c.id=s.customer_id WHERE s.id=? AND s.business_id=? AND s.branch_id=? LIMIT 1");$q->execute([$saleId,$businessId,$branchId]);$sale=$q->fetch();if(!$sale)throw new RuntimeException('Venta no encontrada.');
  $i=$this->db->prepare("SELECT si.id,si.product_id,si.description,si.qty,si.unit_price,si.line_total,COALESCE((SELECT SUM(ri.qty) FROM refund_items ri JOIN refunds r ON r.id=ri.refund_id WHERE ri.sale_item_id=si.id AND r.status='completed'),0) returned_qty FROM sale_items si WHERE si.sale_id=?");$i->execute([$saleId]);
  $p=$this->db->prepare("SELECT p.method,p.amount,p.reference FROM payments p WHERE p.sale_id=?");$p->execute([$saleId]);return ['sale'=>$sale,'items'=>$i->fetchAll(),'payments'=>$p->fetchAll()];
 }
}