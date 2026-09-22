<?php
declare(strict_types=1);
use Vendi\Http\ApiGuard;use Vendi\Reports\ReportCatalog;
require dirname(__DIR__,3).'/vendor/autoload.php';session_start();header('Content-Type: application/json; charset=utf-8');
try{ApiGuard::session();echo json_encode(['ok'=>true,'groups'=>ReportCatalog::groups()],JSON_UNESCAPED_UNICODE);}catch(Throwable$e){http_response_code(401);echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);}
