<?php
declare(strict_types=1);
namespace Vendi\Reports;
use PDO;
final class ProductInventoryReportService{
 public function __construct(private PDO $db){}
 public function products(int $b,int $branch,string $type):array{
  $sql=match($type){
   'products_overview'=>"SELECT p.id,p.name,COALESCE(pc.name,'Sin categoría') category,COALESCE(i.qty,0) stock,COALESCE(p.average_cost,p.cost,0) avg_cost,p.price,COALESCE(i.qty,0)*COALESCE(p.average_cost,p.cost,0) inventory_value FROM products p LEFT JOIN product_categories pc ON pc.id=p.category_id LEFT JOIN inventory i ON i.product_id=p.id AND i.branch_id=? AND i.business_id=p.business_id WHERE p.business_id=? AND p.active=1 ORDER BY p.name",
   'products_top'=>"SELECT p.id,p.name,COALESCE(pc.name,'Sin categoría') category,SUM(si.qty) qty,SUM(si.line_total) sales,SUM(si.line_total-(si.unit_cost*si.qty)) profit FROM sale_items si JOIN sales s ON s.id=si.sale_id JOIN products p ON p.id=si.product_id LEFT JOIN product_categories pc ON pc.id=p.category_id WHERE s.business_id=? AND s.branch_id=? AND s.status='completed' GROUP BY p.id,p.name,pc.name ORDER BY sales DESC LIMIT 100",
   'products_slow'=>"SELECT p.id,p.name,COALESCE(pc.name,'Sin categoría') category,COALESCE(SUM(CASE WHEN s.status='completed' THEN si.qty ELSE 0 END),0) qty,COALESCE(i.qty,0) stock FROM products p LEFT JOIN product_categories pc ON pc.id=p.category_id LEFT JOIN sale_items si ON si.product_id=p.id LEFT JOIN sales s ON s.id=si.sale_id AND s.business_id=p.business_id AND s.branch_id=? LEFT JOIN inventory i ON i.product_id=p.id AND i.branch_id=? WHERE p.business_id=? AND p.active=1 GROUP BY p.id,p.name,pc.name,i.qty ORDER BY qty ASC,p.name LIMIT 100",
   'inventory_status'=>"SELECT p.id,p.name,COALESCE(pc.name,'Sin categoría') category,COALESCE(i.qty,0) stock,COALESCE(i.reserved_qty,0) reserved,COALESCE(p.min_stock,i.min_qty,0) minimum,COALESCE(p.average_cost,p.cost,0) avg_cost,COALESCE(i.qty,0)*COALESCE(p.average_cost,p.cost,0) inventory_value FROM products p LEFT JOIN product_categories pc ON pc.id=p.category_id LEFT JOIN inventory i ON i.product_id=p.id AND i.branch_id=? AND i.business_id=p.business_id WHERE p.business_id=? AND p.active=1 ORDER BY p.name",
   'inventory_low'=>"SELECT p.id,p.name,COALESCE(pc.name,'Sin categoría') category,COALESCE(i.qty,0) stock,COALESCE(p.min_stock,i.min_qty,0) minimum FROM products p JOIN inventory i ON i.product_id=p.id AND i.branch_id=? LEFT JOIN product_categories pc ON pc.id=p.category_id WHERE p.business_id=? AND p.active=1 AND i.qty<=COALESCE(p.min_stock,i.min_qty,0) ORDER BY i.qty ASC",
   'inventory_expiring'=>"SELECT p.name,l.lot_number,l.expires_at,l.qty_base stock,l.cost_per_base FROM inventory_lots l JOIN products p ON p.id=l.product_id WHERE l.business_id=? AND l.branch_id=? AND l.qty_base>0 AND l.expires_at IS NOT NULL ORDER BY l.expires_at ASC LIMIT 250",
   default=>throw new \RuntimeException('Reporte no reconocido.')
  };
  $q=$this->db->prepare($sql);
  if(in_array($type,['products_overview','inventory_status','inventory_low'],true))$q->execute([$branch,$b]);
  elseif($type==='products_slow')$q->execute([$branch,$branch,$b]);
  else $q->execute([$b,$branch]);
  return $q->fetchAll();
 }
 public function categoryProfit(int$b,int$branch):array{$q=$this->db->prepare("SELECT COALESCE(pc.name,'Sin categoría') category,SUM(si.line_total) sales,SUM(si.unit_cost*si.qty) cost,SUM(si.line_total-(si.unit_cost*si.qty)) profit FROM sale_items si JOIN sales s ON s.id=si.sale_id JOIN products p ON p.id=si.product_id LEFT JOIN product_categories pc ON pc.id=p.category_id WHERE s.business_id=? AND s.branch_id=? AND s.status='completed' GROUP BY pc.id,pc.name ORDER BY sales DESC");$q->execute([$b,$branch]);return$q->fetchAll();}
 public function priceHistory(int$b,int$branch):array{$q=$this->db->prepare("SELECT p.name product,h.old_cost,h.new_cost,h.source,h.reference_id,u.name employee,h.created_at FROM product_cost_history h JOIN products p ON p.id=h.product_id JOIN users u ON u.id=h.user_id WHERE h.business_id=? AND h.branch_id=? ORDER BY h.created_at DESC LIMIT 500");$q->execute([$b,$branch]);return$q->fetchAll();}
 public function serialSales(int$b,int$branch):array{$q=$this->db->prepare("SELECT p.name,se.serial_number,se.sale_id,se.updated_at FROM inventory_serials se JOIN products p ON p.id=se.product_id WHERE se.business_id=? AND se.branch_id=? AND se.status='sold' ORDER BY se.updated_at DESC LIMIT 500");$q->execute([$b,$branch]);return$q->fetchAll();}
 public function inventoryMovements(int$b,int$branch):array{$q=$this->db->prepare("SELECT sm.id,p.name product,sm.type,sm.qty,sm.unit_cost,sm.reference_type,sm.reference_id,u.name employee,sm.created_at FROM stock_movements sm JOIN products p ON p.id=sm.product_id JOIN users u ON u.id=sm.user_id WHERE sm.business_id=? AND sm.branch_id=? ORDER BY sm.created_at DESC LIMIT 1000");$q->execute([$b,$branch]);return$q->fetchAll();}
 public function serials(int$b,int$branch):array{$q=$this->db->prepare("SELECT p.name,se.serial_number,se.status,se.sale_id,se.updated_at FROM inventory_serials se JOIN products p ON p.id=se.product_id WHERE se.business_id=? AND se.branch_id=? ORDER BY se.updated_at DESC LIMIT 250");$q->execute([$b,$branch]);return$q->fetchAll();}
}