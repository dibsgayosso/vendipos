<?php
declare(strict_types=1);
namespace Vendi\Auth;
use PDO;
final class PermissionService {
 public function __construct(private PDO $db){}
 public function can(int $userId,string $permission): bool {
  $q=$this->db->prepare("SELECT up.allowed FROM user_permissions up JOIN permissions p ON p.id=up.permission_id WHERE up.user_id=? AND p.code=? LIMIT 1");
  $q->execute([$userId,$permission]);$v=$q->fetchColumn();return $v!==false&&(bool)$v;
 }
 public function matrix(int $userId): array {
  $q=$this->db->prepare("SELECT p.module,p.code,p.name,COALESCE(up.allowed,0) allowed FROM permissions p LEFT JOIN user_permissions up ON up.permission_id=p.id AND up.user_id=? ORDER BY p.module,p.name");
  $q->execute([$userId]);return $q->fetchAll();
 }
}