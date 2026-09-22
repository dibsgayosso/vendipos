<?php
declare(strict_types=1);
namespace Vendi\Products;
use PDO;use RuntimeException;
final class VariantService{
 public function __construct(private PDO $db){}
 public function list(int $businessId,int $productId,int $branchId):array{$q=$this->db->prepare("SELECT v.*,COALESCE(i.quantity,0) stock FROM product_variants v LEFT JOIN variant_inventory i ON i.variant_id=v.id AND i.branch_id=? WHERE v.business_id=? AND v.product_id=? AND v.active=1 ORDER BY v.name");$q->execute([$branchId,$businessId,$productId]);return$q->fetchAll();}
 public function options(int $businessId,int $productId):array{$q=$this->db->prepare("SELECT g.id group_id,g.name group_name,g.option_type,g.required,g.min_select,g.max_select,v.id value_id,v.name value_name,v.code,v.price_delta,v.cost_delta FROM product_option_groups g JOIN product_option_values v ON v.group_id=g.id AND v.active=1 WHERE g.business_id=? AND g.product_id=? AND g.active=1 ORDER BY g.sort_order,g.id,v.sort_order,v.id");$q->execute([$businessId,$productId]);$out=[];foreach($q->fetchAll() as $r){$id=(int)$r['group_id'];if(!isset($out[$id]))$out[$id]=['id'=>$id,'name'=>$r['group_name'],'type'=>$r['option_type'],'required'=>(bool)$r['required'],'min_select'=>(int)$r['min_select'],'max_select'=>$r['max_select']===null?null:(int)$r['max_select'],'values'=>[]];$out[$id]['values'][]=['id'=>(int)$r['value_id'],'name'=>$r['value_name'],'code'=>$r['code'],'price_delta'=>(float)$r['price_delta'],'cost_delta'=>(float)$r['cost_delta']];}return array_values($out);}
}