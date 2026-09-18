<?php
declare(strict_types=1);
namespace Vendi\Sales;
final class PaymentValidator{
 public const METHODS=['cash','card','transfer','credit','other'];
 public static function validate(array $payments,float $total):array{
  $sum=0.0;foreach($payments as $p){$method=(string)($p['method']??'');$amount=(float)($p['amount']??0);if(!in_array($method,self::METHODS,true)||$amount<=0)throw new \InvalidArgumentException('Pago inválido.');$sum+=$amount;}
  if(round($sum,2)<round($total,2))throw new \InvalidArgumentException('Pago insuficiente.');return $payments;
 }
}