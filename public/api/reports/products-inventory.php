<?php
declare(strict_types=1);
use Vendi\Database\Connection;use Vendi\Http\ApiGuard;use Vendi\Branches\BranchContextService;use Vendi\Reports\ProductInventoryReportService;
require dirname(__DIR__,3).'/vendor/autoload.php';session_start();header('Content-Type: application/json; charset=utf-8');
try{$u=ApiGuard::session();ApiGuard::permission('reports.view');$db=Connection::get();$b=(int)$u['business_id'];$ctx=new BranchContextService($db);$branch=(int)$ctx->activeBranchId($u);ApiGuard::branch($branch);$type=(string)($_GET['type']??'products_overview');$s=new ProductInventoryReportService($db);$data=match($type){'category_profit'=>$s->categoryProfit($b,$branch),'serials'=>$s->serials($b,$branch),default=>$s->products($b,$branch,$type)};echo json_encode(['ok'=>true,'type'=>$type,'data'=>$data],JSON_UNESCAPED_UNICODE);}catch(Throwable$e){http_response_code(400);echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);}
