<?php
declare(strict_types=1);
namespace Vendi\Sales;
use PDO;
use RuntimeException;
final class QuickSaleService {
 public function __construct(private PDO $db,private SaleService $sales){}
 public function finish(int $businessId,int $branchId,int $userId,array $items,string $idempotencyKey,string $method='cash'): int {
  $q=$this->db->prepare("SELECT id FROM cash_sessions WHERE business_id=? AND branch_id=? AND user_id=? AND status='open' ORDER BY id DESC LIMIT 1");
  $q->execute([$businessId,$branchId,$userId]);
  if(!$q->fetchColumn()) throw new RuntimeException('Abre caja antes de terminar una venta rápida.');
  if(!$items) throw new RuntimeException('No hay productos en la venta.');
  $total=0.0;
  foreach($items as $i){
   $p=$this->db->prepare("SELECT price,tax_rate FROM products WHERE id=? AND business_id=? AND active=1 LIMIT 1");
   $p->execute([(int)$i['product_id'],$businessId]);$r=$p->fetch();
   if(!$r) throw new RuntimeException('Producto inválido.');
   $line=\Vendi\Support\Money::calculateLine((float)$i['qty'],(float)$r['price'],(float)($i['discount_percent']??0),(float)$r['tax_rate']);
   $total+=$line['total'];
  }
  return $this->sales->checkout($businessId,$branchId,$userId,null,$items,[['method'=>$method,'amount'=>\Vendi\Support\Money::round($total)]],$idempotencyKey);
 }
}