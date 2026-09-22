<?php
declare(strict_types=1);
namespace Vendi\Reports;
use PDO;use RuntimeException;use Vendi\Branches\BranchClock;
final class ReportBuilderService{
 public function __construct(private PDO $db){}
 public function definitions():array{return[
  'sales'=>['name'=>'Ventas','dimensions'=>['date'=>'Fecha','employee'=>'Empleado','customer'=>'Cliente','payment'=>'Método de pago'],'metrics'=>['tickets'=>'Tickets','sales'=>'Ventas','cost'=>'Costo','profit'=>'Utilidad','discount'=>'Descuentos','tax'=>'Impuestos']],
  'products'=>['name'=>'Productos vendidos','dimensions'=>['product'=>'Producto','category'=>'Categoría','employee'=>'Empleado'],'metrics'=>['qty'=>'Cantidad','sales'=>'Ventas','cost'=>'Costo','profit'=>'Utilidad']],
  'customers'=>['name'=>'Clientes','dimensions'=>['customer'=>'Cliente','employee'=>'Empleado'],'metrics'=>['tickets'=>'Tickets','sales'=>'Compras','average_ticket'=>'Ticket promedio']]
 ];}
 public function run(int$b,int$branch,string$f,string$t,string$dataset,array$dims,array$metrics,string$order='sales',string$direction='desc'):array{
  $defs=$this->definitions();if(!isset($defs[$dataset]))throw new RuntimeException('Origen de datos inválido.');$dims=array_values(array_intersect($dims,array_keys($defs[$dataset]['dimensions'])));$metrics=array_values(array_intersect($metrics,array_keys($defs[$dataset]['metrics'])));if(!$dims&&!$metrics)throw new RuntimeException('Selecciona al menos una columna.');
  $clock=new BranchClock($this->db);[$a]=$clock->utcRangeForLocalDate($b,$branch,$f);[, $z]=$clock->utcRangeForLocalDate($b,$branch,$t);
  $map=$dataset==='products'?[
   'product'=>"p.name",'category'=>"COALESCE(pc.name,'Sin categoría')",'employee'=>"u.name",'qty'=>"SUM(si.qty)",'sales'=>"SUM(si.line_total)",'cost'=>"SUM(si.unit_cost*si.qty)",'profit'=>"SUM(si.line_total-(si.unit_cost*si.qty))"
  ]:($dataset==='customers'?[
   'customer'=>"COALESCE(c.name,'Público general')",'employee'=>"u.name",'tickets'=>"COUNT(DISTINCT s.id)",'sales'=>"SUM(s.total)",'average_ticket'=>"AVG(s.total)"
  ]:[
   'date'=>"DATE(s.completed_at_utc)",'employee'=>"u.name",'customer'=>"COALESCE(c.name,'Público general')",'payment'=>"COALESCE(pm.name,pay.method)",'tickets'=>"COUNT(DISTINCT s.id)",'sales'=>"SUM(s.total)",'cost'=>"SUM(s.cost_total)",'profit'=>"SUM(s.total-s.cost_total)",'discount'=>"SUM(s.discount)",'tax'=>"SUM(s.tax)"
  ]);
  $cols=array_merge($dims,$metrics);$select=[];foreach($cols as$c)$select[]=$map[$c]." AS ".$c;$group=[];foreach($dims as$d)$group[]=$map[$d];
  if($dataset==='products')$from="sale_items si JOIN sales s ON s.id=si.sale_id JOIN products p ON p.id=si.product_id LEFT JOIN product_categories pc ON pc.id=p.category_id JOIN users u ON u.id=s.user_id";
  elseif($dataset==='customers')$from="sales s LEFT JOIN customers c ON c.id=s.customer_id JOIN users u ON u.id=s.user_id";
  else $from="sales s JOIN users u ON u.id=s.user_id LEFT JOIN customers c ON c.id=s.customer_id LEFT JOIN payments pay ON pay.sale_id=s.id LEFT JOIN payment_methods pm ON pm.id=pay.payment_method_id";
  $order=in_array($order,$cols,true)?$order:($metrics[0]??$dims[0]);$direction=strtolower($direction)==='asc'?'ASC':'DESC';$sql="SELECT ".implode(',',$select)." FROM ".$from." WHERE s.business_id=? AND s.branch_id=? AND s.status='completed' AND s.completed_at_utc>=? AND s.completed_at_utc<?".($group?" GROUP BY ".implode(',',$group):"")." ORDER BY ".$order." ".$direction." LIMIT 1000";$q=$this->db->prepare($sql);$q->execute([$b,$branch,$a,$z]);return['columns'=>$cols,'rows'=>$q->fetchAll()];
 }
}