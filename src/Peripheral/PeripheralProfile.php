<?php
declare(strict_types=1);
namespace Vendi\Peripheral;
final class PeripheralProfile {
 public const TYPES=['scale','barcode_scanner','receipt_printer','cash_drawer','customer_display'];
 public static function validate(array $data): array {
  $type=(string)($data['type']??''); if(!in_array($type,self::TYPES,true)) throw new \InvalidArgumentException('Tipo de periférico inválido.');
  $transport=(string)($data['transport']??''); if(!in_array($transport,['web_serial','web_hid','bridge','keyboard'],true)) throw new \InvalidArgumentException('Transporte inválido.');
  return ['type'=>$type,'transport'=>$transport,'name'=>trim((string)($data['name']??$type)),'config'=>$data['config']??[]];
 }
}