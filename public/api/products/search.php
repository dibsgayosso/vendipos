<?php
declare(strict_types=1);
use Vendi\Database\Connection;
use Vendi\Products\ProductSearchService;
require dirname(__DIR__,3).'/vendor/autoload.php';
header('Content-Type: application/json; charset=utf-8');
try{
 $term=(string)($_GET['q']??''); if(mb_strlen(trim($term))<3){echo json_encode(['items'=>[]]);exit;}
 // TODO auth middleware will replace these session fallbacks.
 session_start();$businessId=(int)($_SESSION['user']['business_id']??0);$branchId=(int)($_SESSION['user']['default_branch_id']??0);
 if(!$businessId||!$branchId){http_response_code(401);echo json_encode(['error'=>'Sesión requerida']);exit;}
 $db=Connection::make(['host'=>getenv('DB_HOST')?:'127.0.0.1','port'=>(int)(getenv('DB_PORT')?:3306),'database'=>getenv('DB_DATABASE')?:'vendi','username'=>getenv('DB_USERNAME')?:'root','password'=>getenv('DB_PASSWORD')?:'']);
 echo json_encode(['items'=>(new ProductSearchService($db))->search($businessId,$branchId,$term)],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
}catch(Throwable $e){http_response_code(500);echo json_encode(['error'=>'No fue posible buscar productos']);}