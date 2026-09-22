<?php
declare(strict_types=1);
namespace Vendi\Database;
use PDO;
final class Connection {
 private static ?PDO $shared=null;
 public static function get(): PDO {if(self::$shared instanceof PDO)return self::$shared;return self::$shared=self::make(['host'=>getenv('DB_HOST')?:'127.0.0.1','port'=>(int)(getenv('DB_PORT')?:3306),'database'=>getenv('DB_DATABASE')?:'vendi','username'=>getenv('DB_USERNAME')?:'root','password'=>getenv('DB_PASSWORD')?:'']);}

 public static function make(array $c): PDO {
  $dsn=sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',$c['host'],$c['port']??3306,$c['database']);
  return new PDO($dsn,$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
 }
}