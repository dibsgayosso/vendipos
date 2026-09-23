<?php
declare(strict_types=1);
require __DIR__.'/../src/autoload.php';
use Vendi\Database\Connection;
if(PHP_SAPI!=='cli'){http_response_code(403);exit("Ejecuta este instalador por CLI.\n");}
try{$db=Connection::get();echo "Conexión a MySQL: OK\n";}catch(Throwable $e){fwrite(STDERR,"No se pudo conectar a MySQL: ".$e->getMessage()."\n");exit(1);}
$db->exec("CREATE TABLE IF NOT EXISTS vendi_migrations(filename VARCHAR(190) PRIMARY KEY,applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$hasBase=(bool)$db->query("SHOW TABLES LIKE 'businesses'")->fetchColumn();
if(!$hasBase){echo "Creando esquema base... ";$db->exec((string)file_get_contents(__DIR__.'/../database/schema.sql'));echo "OK\n";}
$done=$db->query("SELECT filename FROM vendi_migrations")->fetchAll(PDO::FETCH_COLUMN);
$files=glob(__DIR__.'/../database/[0-9][0-9][0-9]_*.sql')?:[];sort($files,SORT_NATURAL);
foreach($files as$file){$name=basename($file);if(in_array($name,$done,true))continue;echo "Aplicando $name... ";try{$db->exec((string)file_get_contents($file));$q=$db->prepare("INSERT INTO vendi_migrations(filename) VALUES(?)");$q->execute([$name]);echo "OK\n";}catch(Throwable$e){fwrite(STDERR,"ERROR en $name: ".$e->getMessage()."\n");exit(1);}}
echo "Vendi POS instalado correctamente.\n";
