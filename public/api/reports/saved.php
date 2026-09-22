<?php
declare(strict_types=1);
use Vendi\Database\Connection;use Vendi\Http\ApiGuard;
require dirname(__DIR__,3).'/vendor/autoload.php';session_start();header('Content-Type: application/json; charset=utf-8');
try{$u=ApiGuard::session();ApiGuard::permission('reports.saved');$db=Connection::get();$b=(int)$u['business_id'];$uid=(int)$u['id'];
if($_SERVER['REQUEST_METHOD']==='GET'){$q=$db->prepare("SELECT id,name,report_key,filters_json,is_shared,created_at,updated_at FROM saved_reports WHERE business_id=? AND (user_id=? OR is_shared=1) ORDER BY updated_at DESC");$q->execute([$b,$uid]);echo json_encode(['ok'=>true,'data'=>$q->fetchAll()],JSON_UNESCAPED_UNICODE);exit;}
ApiGuard::csrf();$x=json_decode(file_get_contents('php://input'),true)?:[];$action=(string)($x['action']??'save');
if($action==='delete'){$q=$db->prepare("DELETE FROM saved_reports WHERE id=? AND business_id=? AND user_id=?");$q->execute([(int)$x['id'],$b,$uid]);echo json_encode(['ok'=>true]);exit;}
$name=trim((string)($x['name']??''));$key=trim((string)($x['report_key']??''));if($name===''||$key==='')throw new RuntimeException('Nombre y reporte son obligatorios.');$filters=json_encode($x['filters']??[],JSON_UNESCAPED_UNICODE);$q=$db->prepare("INSERT INTO saved_reports(business_id,user_id,name,report_key,filters_json,is_shared) VALUES(?,?,?,?,?,?)");$q->execute([$b,$uid,$name,$key,$filters,!empty($x['is_shared'])?1:0]);echo json_encode(['ok'=>true,'id'=>(int)$db->lastInsertId()]);}catch(Throwable$e){http_response_code(400);echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);}
