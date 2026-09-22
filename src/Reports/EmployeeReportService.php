<?php
declare(strict_types=1);
namespace Vendi\Reports;
use PDO;use Vendi\Branches\BranchClock;
final class EmployeeReportService{
 public function __construct(private PDO $db){}
 private function range(int$b,int$branch,string$from,string$to):array{$c=new BranchClock($this->db);[$a]=$c->utcRangeForLocalDate($b,$branch,$from);[, $z]=$c->utcRangeForLocalDate($b,$branch,$to);return[$a,$z];}
 public function performance(int$b,int$branch,string$from,string$to):array{[$a,$z]=$this->range($b,$branch,$from,$to);$q=$this->db->prepare("SELECT u.id,u.name,COUNT(s.id) tickets,COALESCE(SUM(s.total),0) sales,COALESCE(SUM(s.total-s.cost_total),0) profit,COALESCE(AVG(s.total),0) average_ticket,COALESCE(SUM(sc.commission_amount),0) commissions FROM users u LEFT JOIN sales s ON s.user_id=u.id AND s.business_id=u.business_id AND s.branch_id=? AND s.status='completed' AND s.completed_at_utc>=? AND s.completed_at_utc<? LEFT JOIN sale_commissions sc ON sc.sale_id=s.id AND sc.user_id=u.id AND sc.status<>'cancelled' WHERE u.business_id=? AND u.status='active' GROUP BY u.id,u.name ORDER BY sales DESC");$q->execute([$branch,$a,$z,$b]);return$q->fetchAll();}
 public function commissions(int$b,int$branch,string$from,string$to):array{[$a,$z]=$this->range($b,$branch,$from,$to);$q=$this->db->prepare("SELECT sc.id,sc.sale_id,u.name employee,sc.basis,sc.base_amount,sc.rate_percent,sc.commission_amount,sc.status,sc.created_at FROM sale_commissions sc JOIN users u ON u.id=sc.user_id JOIN sales s ON s.id=sc.sale_id WHERE sc.business_id=? AND sc.branch_id=? AND s.completed_at_utc>=? AND s.completed_at_utc<? ORDER BY sc.created_at DESC");$q->execute([$b,$branch,$a,$z]);return$q->fetchAll();}
}