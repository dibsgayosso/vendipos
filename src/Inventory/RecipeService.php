<?php
declare(strict_types=1);
namespace Vendi\Inventory;
use PDO;use RuntimeException;
final class RecipeService{
 public function __construct(private PDO $db){}
 public function components(int $businessId,int $parentProductId):array{$q=$this->db->prepare("SELECT pc.*,p.name component_name FROM product_components pc JOIN products p ON p.id=pc.component_product_id WHERE pc.business_id=? AND pc.parent_product_id=? ORDER BY pc.id");$q->execute([$businessId,$parentProductId]);return$q->fetchAll();}
 public function consumption(int $businessId,int $parentProductId,float $qty):array{if($qty<=0)throw new RuntimeException('Cantidad inválida.');$out=[];foreach($this->components($businessId,$parentProductId) as $c){$base=(float)$c['quantity_base']*$qty;$out[]=['product_id'=>(int)$c['component_product_id'],'variant_id'=>$c['component_variant_id']? (int)$c['component_variant_id']:null,'quantity_base'=>round($base*(1+((float)$c['waste_percent']/100)),6),'optional'=>(bool)$c['optional']];}return$out;}
}