<?php
declare(strict_types=1);
namespace Vendi\Database;
use PDO;
final class Connection {
 private static ?PDO $shared=null;
 private static bool $envLoaded=false;
 private static function loadEnv():void{
  if(self::$envLoaded)return;self::$envLoaded=true;
  $file=dirname(__DIR__,2).'/.env';if(!is_file($file)||!is_readable($file))return;
  foreach(file($file,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES)?:[] as $line){
   $line=trim($line);if($line===''||str_starts_with($line,'#')||!str_contains($line,'='))continue;
   [$key,$value]=array_map('trim',explode('=',$line,2));if($key===''||getenv($key)!==false)continue;
   if(strlen($value)>=2&&(($value[0]==='"'&&$value[-1]==='"')||($value[0]==="'"&&$value[-1]==="'")))$value=substr($value,1,-1);
   putenv($key.'='.$value);$_ENV[$key]=$value;$_SERVER[$key]=$value;
  }
 }
 public static function get(): PDO {
  if(self::$shared instanceof PDO)return self::$shared;self::loadEnv();
  return self::$shared=self::make(['host'=>getenv('DB_HOST')?:'localhost','port'=>(int)(getenv('DB_PORT')?:3306),'database'=>getenv('DB_DATABASE')?:'samy5155_vendi','username'=>getenv('DB_USERNAME')?:'samy5155_vendi','password'=>getenv('DB_PASSWORD')?:'']);
 }
 public static function make(array $c): PDO {
  $dsn=sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',$c['host'],$c['port']??3306,$c['database']);
  return new PDO($dsn,$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
 }
}
