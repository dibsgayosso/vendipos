<?php
declare(strict_types=1);
namespace Vendi\Auth;
use PDO;
final class AuthService {
 public function __construct(private PDO $db){}
 public function attempt(int $businessId,string $email,string $password): ?array {
  $q=$this->db->prepare("SELECT id,business_id,default_branch_id,name,email,password_hash,role,status FROM users WHERE business_id=? AND email=? LIMIT 1");
  $q->execute([$businessId,mb_strtolower(trim($email))]); $u=$q->fetch();
  if(!$u||$u['status']!=='active'||!password_verify($password,$u['password_hash'])) return null;
  unset($u['password_hash']); return $u;
 }
}