<?php
declare(strict_types=1);
namespace Vendi\Cash;
use PDO;
use RuntimeException;
final class CashService {
 public function __construct(private PDO $db){}
 public function open(int $businessId,int $branchId,int $userId,float $opening): int {
  $q=$this->db->prepare("SELECT id FROM cash_sessions WHERE business_id=? AND branch_id=? AND user_id=? AND status='open' LIMIT 1");
  $q->execute([$businessId,$branchId,$userId]);if($q->fetch())throw new RuntimeException('Ya existe una caja abierta.');
  $i=$this->db->prepare("INSERT INTO cash_sessions(business_id,branch_id,user_id,opening_amount,status,opened_at) VALUES(?,?,?,?,'open',NOW())");
  $i->execute([$businessId,$branchId,$userId,$opening]);return(int)$this->db->lastInsertId();
 }
 public function close(int $businessId,int $branchId,int $userId,float $counted): array {
  $this->db->beginTransaction();
  try{
   $q=$this->db->prepare("SELECT * FROM cash_sessions WHERE business_id=? AND branch_id=? AND user_id=? AND status='open' ORDER BY id DESC LIMIT 1 FOR UPDATE");
   $q->execute([$businessId,$branchId,$userId]);$s=$q->fetch();if(!$s)throw new RuntimeException('No hay caja abierta.');
   $p=$this->db->prepare("SELECT COALESCE(SUM(p.amount),0) FROM payments p JOIN sales s ON s.id=p.sale_id WHERE s.business_id=? AND s.branch_id=? AND s.user_id=? AND s.completed_at>=? AND p.method='cash' AND s.status='completed'");
   $p->execute([$businessId,$branchId,$userId,$s['opened_at']]);$cashSales=(float)$p->fetchColumn();
   $expected=(float)$s['opening_amount']+$cashSales;$diff=$counted-$expected;
   $u=$this->db->prepare("UPDATE cash_sessions SET closing_amount=?,expected_amount=?,difference_amount=?,status='closed',closed_at=NOW() WHERE id=?");
   $u->execute([$counted,$expected,$diff,$s['id']]);$this->db->commit();
   return ['session_id'=>(int)$s['id'],'expected'=>$expected,'counted'=>$counted,'difference'=>$diff];
  }catch(\Throwable $e){$this->db->rollBack();throw $e;}
 }
}