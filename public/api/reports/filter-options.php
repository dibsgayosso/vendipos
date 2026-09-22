<?php
declare(strict_types=1);
use Vendi\Database\Connection;use Vendi\Http\ApiGuard;use Vendi\Branches\BranchContextService;
require dirname(__DIR__,3).'/vendor/autoload.php';session_start();header('Content-Type: application/json; charset=utf-8');
try{$u=ApiGuard::session();ApiGuard::permission($db,$u,'reports.view');$db=Connection::get();$b=(int)$u['business_id'];$branch=(int)(new BranchContextService($db))->activeBranchId($u);ApiGuard::branch($db,$u,$branch);$type=(string)($_GET['type']??'');$q=trim((string)($_GET['q']??''));if(mb_strlen($q)<2){echo json_encode(['ok'=>true,'items'=>[]]);exit;}$like='%'.$q.'%';
$sql=match($type){
 'customer'=>"SELECT id,name label,CONCAT_WS(' · ',NULLIF(phone,''),NULLIF(email,'')) detail FROM customers WHERE business_id=? AND (name LIKE ? OR phone LIKE ? OR email LIKE ? OR rfc LIKE ?) ORDER BY name LIMIT 15",
 'employee'=>"SELECT u.id,u.name label,u.email detail FROM users u LEFT JOIN user_branches ub ON ub.user_id=u.id AND ub.branch_id=? WHERE u.business_id=? AND u.status='active' AND (u.default_branch_id=? OR ub.branch_id=? OR u.role='owner') AND (u.name LIKE ? OR u.email LIKE ?) GROUP BY u.id ORDER BY u.name LIMIT 15",
 'product'=>"SELECT p.id,p.name label,CONCAT_WS(' · ',NULLIF(p.sku,''),NULLIF(p.barcode,'')) detail FROM products p WHERE p.business_id=? AND p.active=1 AND (p.name LIKE ? OR p.sku LIKE ? OR p.barcode LIKE ?) ORDER BY p.name LIMIT 15",
 'category'=>"SELECT id,name label,'' detail FROM product_categories WHERE business_id=? AND active=1 AND name LIKE ? ORDER BY name LIMIT 15",
 'payment'=>"SELECT id,name label,'' detail FROM payment_methods WHERE business_id=? AND active=1 AND name LIKE ? ORDER BY sort_order,name LIMIT 15",
 default=>throw new RuntimeException('Tipo de búsqueda inválido.')
};
$st=$db->prepare($sql);$params=match($type){'customer'=>[$b,$like,$like,$like,$like],'employee'=>[$branch,$b,$branch,$branch,$like,$like],'product'=>[$b,$like,$like,$like],'category','payment'=>[$b,$like]};$st->execute($params);echo json_encode(['ok'=>true,'items'=>$st->fetchAll()],JSON_UNESCAPED_UNICODE);}catch(Throwable$e){http_response_code(400);echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);}
