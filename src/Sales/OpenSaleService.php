<?php
declare(strict_types=1);
namespace Vendi\Sales;
use PDO;
final class OpenSaleService {
 public function __construct(private PDO $db){}
 public function save(int $businessId,int $branchId,int $userId,?int $id,string $name,array $cart): int {
  $json=json_encode($cart,JSON_THROW_ON_ERROR);
  if($id){$q=$this->db->prepare("UPDATE open_sale_tabs SET name=?,cart_json=? WHERE id=? AND business_id=? AND branch_id=? AND user_id=?");$q->execute([$name,$json,$id,$businessId,$branchId,$userId]);return $id;}
  $q=$this->db->prepare("INSERT INTO open_sale_tabs(business_id,branch_id,user_id,name,cart_json) VALUES(?,?,?,?,?)");$q->execute([$businessId,$branchId,$userId,$name,$json]);return (int)$this->db->lastInsertId();
 }
 public function all(int $businessId,int $branchId,int $userId): array {$q=$this->db->prepare("SELECT id,name,cart_json,updated_at FROM open_sale_tabs WHERE business_id=? AND branch_id=? AND user_id=? ORDER BY updated_at DESC");$q->execute([$businessId,$branchId,$userId]);return $q->fetchAll();}
}