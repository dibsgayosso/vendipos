<?php
declare(strict_types=1);
namespace Vendi\Database;
use PDO;
final class Connection {
 public static function make(array $c): PDO {
  $dsn=sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',$c['host'],$c['port']??3306,$c['database']);
  return new PDO($dsn,$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
 }
}