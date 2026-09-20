<?php
declare(strict_types=1);
namespace Vendi\Payments;
use PDO;use RuntimeException;
final class PaymentMethodService{
 public function __construct(private PDO $db){}
 public function available(int $businessId,int $branchId):array{
  $q=$this->db->prepare("SELECT pm.id,pm.code,pm.name,pm.type FROM payment_methods pm LEFT JOIN branch_payment_methods bpm ON bpm.payment_method_id=pm.id AND bpm.branch_id=? WHERE pm.business_id=? AND pm.active=1 AND COALESCE(bpm.enabled,1)=1 ORDER BY pm.sort_order,pm.name");
  $q->execute([$branchId,$businessId]);return $q->fetchAll();
 }
 public function create(int $businessId,string $code,string $name,string $type):int{
  if(!in_array($type,['cash','card','transfer','credit','other'],true))throw new RuntimeException('Tipo inválido.');
  $q=$this->db->prepare("INSERT INTO payment_methods(business_id,code,name,type) VALUES(?,?,?,?)");$q->execute([$businessId,$code,$name,$type]);return (int)$this->db->lastInsertId();
 }
}