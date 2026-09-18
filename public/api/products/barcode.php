<?php
declare(strict_types=1);
use Vendi\Database\Connection;use Vendi\Products\ProductRepository;
require dirname(__DIR__,3).'/vendor/autoload.php';session_start();header('Content-Type: application/json; charset=utf-8');
$b=(int)($_SESSION['user']['business_id']??0);$br=(int)($_SESSION['user']['default_branch_id']??0);if(!$b||!$br){http_response_code(401);echo json_encode(['error'=>'Sesión requerida']);exit;}
$db=Connection::make(['host'=>getenv('DB_HOST')?:'127.0.0.1','port'=>(int)(getenv('DB_PORT')?:3306),'database'=>getenv('DB_DATABASE')?:'vendi','username'=>getenv('DB_USERNAME')?:'root','password'=>getenv('DB_PASSWORD')?:'']);
$p=(new ProductRepository($db))->barcode($b,$br,(string)($_GET['code']??''));if(!$p){http_response_code(404);echo json_encode(['error'=>'Código no encontrado']);exit;}echo json_encode($p,JSON_UNESCAPED_UNICODE);