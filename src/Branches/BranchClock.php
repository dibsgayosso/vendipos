<?php
declare(strict_types=1);namespace Vendi\Branches;use PDO;use DateTimeImmutable;use DateTimeZone;use RuntimeException;
final class BranchClock{public function __construct(private PDO$db){}
 public function timezone(int$b,int$branch):DateTimeZone{$q=$this->db->prepare("SELECT timezone FROM branches WHERE id=? AND business_id=? LIMIT 1");$q->execute([$branch,$b]);$tz=$q->fetchColumn();if(!$tz)throw new RuntimeException('Sucursal no encontrada.');return new DateTimeZone((string)$tz);}
 public function now(int$b,int$branch):DateTimeImmutable{return new DateTimeImmutable('now',$this->timezone($b,$branch));}
 public function localSql(int$b,int$branch):string{return$this->now($b,$branch)->format('Y-m-d H:i:s');}
 public function utcSql(int$b,int$branch):string{return$this->now($b,$branch)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');}
 public function utcRangeForLocalDate(int$b,int$branch,string$date):array{$tz=$this->timezone($b,$branch);$start=new DateTimeImmutable($date.' 00:00:00',$tz);$end=$start->modify('+1 day');$utc=new DateTimeZone('UTC');return[$start->setTimezone($utc)->format('Y-m-d H:i:s'),$end->setTimezone($utc)->format('Y-m-d H:i:s')];}
 public function displayUtc(int$b,int$branch,string$utc):string{return(new DateTimeImmutable($utc,new DateTimeZone('UTC')))->setTimezone($this->timezone($b,$branch))->format('Y-m-d H:i:s');}
}