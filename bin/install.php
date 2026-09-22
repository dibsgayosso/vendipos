<?php
declare(strict_types=1);
require __DIR__.'/../vendor/autoload.php';
use Vendi\Database\Connection;
if(PHP_SAPI!=='cli'){http_response_code(403);exit("Ejecuta este instalador por CLI.\n");}
$db=Connection::get();$dir=__DIR__.'/../database';$files=glob($dir.'/*.sql');sort($files,SORT_NATURAL);$db->exec("CREATE TABLE IF NOT EXISTS vendi_migrations(filename VARCHAR(190) PRIMARY KEY,applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$done=$db->query("SELECT filename FROM vendi_migrations")->fetchAll(PDO::FETCH_COLUMN);foreach($files as$file){$name=basename($file);if($name==='schema.sql'||in_array($name,$done,true))continue;echo "Aplicando $name... ";$sql=file_get_contents($file);try{$db->beginTransaction();$db->exec($sql);$q=$db->prepare("INSERT INTO vendi_migrations(filename) VALUES(?)");$q->execute([$name]);$db->commit();echo "OK\n";}catch(Throwable$e){if($db->inTransaction())$db->rollBack();fwrite(STDERR,"ERROR: ".$e->getMessage()."\n");exit(1);}}
echo "Vendi POS Preview v0.1 listo.\n";
