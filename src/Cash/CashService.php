<?php
declare(strict_types=1);
namespace Vendi\Cash;
use PDO;
use RuntimeException;use Vendi\Branches\BranchClock;
final class CashService {
 public function __construct(private PDO $db){}
 public function open(int $businessId,int $branchId,int $userId,float $opening): int {
  $q=$this->db->prepare("SELECT id FROM cash_sessions WHERE business_id=? AND branch_id=? AND user_id=? AND status='open' LIMIT 1");
  $q->execute([$businessId,$branchId,$userId]);if($q->fetch())throw new RuntimeException('Ya existe una caja abierta.');
  $utc=(new BranchClock($this->db))->utcSql($businessId,$branchId);$i=$this->db->prepare("INSERT INTO cash_sessions(business_id,branch_id,user_id,opening_amount,status,opened_at,opened_at_utc) VALUES(?,?,?,?,'open',?,?)");
  $i->execute([$businessId,$branchId,$userId,$opening,$utc,$utc]);return(int)$this->db->lastInsertId();
 }
 public function close(int $businessId,int $branchId,int $userId,float $counted): array {
  $this->db->beginTransaction();
  try{
   $q=$this->db->prepare("SELECT * FROM cash_sessions WHERE business_id=? AND branch_id=? AND user_id=? AND status='open' ORDER BY id DESC LIMIT 1 FOR UPDATE");
   $q->execute([$businessId,$branchId,$userId]);$s=$q->fetch();if(!$s)throw new RuntimeException('No hay caja abierta.');
   $m=$this->db->prepare("SELECT COALESCE(SUM(CASE WHEN type IN ('sale','deposit') THEN amount WHEN type IN ('refund','withdrawal') THEN -amount ELSE 0 END),0) FROM cash_movements WHERE cash_session_id=?");$m->execute([$s['id']]);$movementNet=(float)$m->fetchColumn();
   $expected=(float)$s['opening_amount']+$movementNet;$diff=$counted-$expected;
   $utc=(new BranchClock($this->db))->utcSql($businessId,$branchId);$u=$this->db->prepare("UPDATE cash_sessions SET closing_amount=?,expected_amount=?,difference_amount=?,status='closed',closed_at=?,closed_at_utc=? WHERE id=?");
   $u->execute([$counted,$expected,$diff,$utc,$utc,$s['id']]);$this->db->commit();
   return ['session_id'=>(int)$s['id'],'expected'=>$expected,'counted'=>$counted,'difference'=>$diff];
  }catch(\Throwable $e){$this->db->rollBack();throw $e;}
 }
}