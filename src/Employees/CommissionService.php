<?php
declare(strict_types=1);
namespace Vendi\Employees;
use PDO;use RuntimeException;
final class CommissionService{
 public function __construct(private PDO $db){}
 public function profile(int$b,int$user):array{$q=$this->db->prepare("SELECT enabled,rate_percent,basis FROM employee_commission_profiles WHERE business_id=? AND user_id=?");$q->execute([$b,$user]);return$q->fetch()?:['enabled'=>0,'rate_percent'=>0,'basis'=>'sales'];}
 public function setProfile(int$b,int$user,bool$enabled,float$rate,string$basis):void{if(!in_array($basis,['sales','profit'],true)||$rate<0||$rate>100)throw new RuntimeException('Configuración de comisión inválida.');$q=$this->db->prepare("INSERT INTO employee_commission_profiles(user_id,business_id,enabled,rate_percent,basis) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE enabled=VALUES(enabled),rate_percent=VALUES(rate_percent),basis=VALUES(basis)");$q->execute([$user,$b,$enabled?1:0,$rate,$basis]);}
 public function freezeForSale(int$b,int$branch,int$sale,int$user,float$total,float$profit):void{$p=$this->profile($b,$user);if(!(int)$p['enabled']||(float)$p['rate_percent']<=0)return;$base=$p['basis']==='profit'?$profit:$total;$amount=round($base*((float)$p['rate_percent']/100),2);$q=$this->db->prepare("INSERT IGNORE INTO sale_commissions(business_id,branch_id,sale_id,user_id,basis,base_amount,rate_percent,commission_amount) VALUES(?,?,?,?,?,?,?,?)");$q->execute([$b,$branch,$sale,$user,$p['basis'],$base,$p['rate_percent'],$amount]);}
}