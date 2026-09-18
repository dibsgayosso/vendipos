<?php
declare(strict_types=1);
namespace Vendi\Cash;
use PDO;
use RuntimeException;
final class CashService {
 public function __construct(private PDO $db){}
 public function open(int $businessId,int $branchId,int $userId,float $opening): int {
  $q=$this->db->prepare("SELECT id FROM cash_sessions WHERE business_id=? AND branch_id=? AND user_id=? AND status='open' LIMIT 1");
  $q->execute([$businessId,$branchId,$userId]); if($q->fetch()) throw new RuntimeException('Ya existe una caja abierta.');
  $i=$this->db->prepare("INSERT INTO cash_sessions(business_id,branch_id,user_id,opening_amount,status,opened_at) VALUES(?,?,?,?,'open',NOW())");
  $i->execute([$businessId,$branchId,$userId,$opening]); return (int)$this->db->lastInsertId();
 }
}