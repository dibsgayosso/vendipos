<?php
declare(strict_types=1);
namespace Vendi\Settings;
use PDO;
final class SettingsService {
 public function __construct(private PDO $db){}
 public function get(int $businessId,?int $branchId,string $key,mixed $default=null): mixed {
  $q=$this->db->prepare("SELECT setting_value FROM settings WHERE business_id=? AND setting_key=? AND (branch_id=? OR branch_id IS NULL) ORDER BY branch_id IS NOT NULL DESC LIMIT 1");
  $q->execute([$businessId,$key,$branchId]);$v=$q->fetchColumn();return $v===false?$default:$v;
 }
 public function set(int $businessId,?int $branchId,string $key,string $value): void {
  $q=$this->db->prepare("INSERT INTO settings(business_id,branch_id,setting_key,setting_value) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");
  $q->execute([$businessId,$branchId,$key,$value]);
 }
}