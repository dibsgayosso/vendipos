<?php
declare(strict_types=1);
namespace Vendi\Products;
use RuntimeException;
final class PriceLevelService{
 public static function price(float $retail,string $calculation,?float $fixed,?float $discount):float{
  if($retail<0)throw new RuntimeException('Precio público inválido.');
  if($calculation==='fixed'){
   if($fixed===null||$fixed<0)throw new RuntimeException('Precio fijo inválido.');
   return round($fixed,4);
  }
  $pct=$discount??0;if($pct<0||$pct>100)throw new RuntimeException('Descuento inválido.');
  return round($retail*(1-($pct/100)),4);
 }
 public static function unitCost(float $packageCost,float $quantity):float{
  if($packageCost<0||$quantity<=0)throw new RuntimeException('Costo o cantidad inválidos.');
  return round($packageCost/$quantity,4);
 }
}