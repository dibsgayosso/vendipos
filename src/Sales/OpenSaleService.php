<?php
declare(strict_types=1);
namespace Vendi\Sales;
use PDO;use RuntimeException;
final class OpenSaleService{
 public function __construct(private PDO $db){}
 public function save(int $businessId,int $branchId,int $userId,string $publicId,string $name,array $items,?int $customerId=null):void{
  if(!preg_match('/^[a-f0-9-]{36}$/i',$publicId))throw new RuntimeException('Identificador inválido.');
  $payload=json_encode(['items'=>$items],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
  $q=$this->db->prepare("SELECT id FROM open_sale_tabs WHERE public_id=? AND business_id=? FOR UPDATE");$this->db->beginTransaction();
  try{$q->execute([$publicId,$businessId]);$id=$q->fetchColumn();if($id){$u=$this->db->prepare("UPDATE open_sale_tabs SET branch_id=?,user_id=?,name=?,customer_id=?,payload_json=?,status='open',updated_at=NOW() WHERE id=?");$u->execute([$branchId,$userId,substr($name,0,100),$customerId,$payload,$id]);}else{$i=$this->db->prepare("INSERT INTO open_sale_tabs(business_id,branch_id,user_id,public_id,name,customer_id,payload_json,status,updated_at) VALUES(?,?,?,?,?,?,?,'open',NOW())");$i->execute([$businessId,$branchId,$userId,$publicId,substr($name,0,100),$customerId,$payload]);}$this->db->commit();}catch(\Throwable $e){$this->db->rollBack();throw $e;}
 }
 public function list(int $businessId,int $branchId):array{$q=$this->db->prepare("SELECT public_id,name,user_id,customer_id,updated_at FROM open_sale_tabs WHERE business_id=? AND branch_id=? AND status='open' ORDER BY updated_at DESC");$q->execute([$businessId,$branchId]);return $q->fetchAll();}
 public function get(int $businessId,int $branchId,string $publicId):array{$q=$this->db->prepare("SELECT public_id,name,customer_id,payload_json,updated_at FROM open_sale_tabs WHERE business_id=? AND branch_id=? AND public_id=? AND status='open' LIMIT 1");$q->execute([$businessId,$branchId,$publicId]);$r=$q->fetch();if(!$r)throw new RuntimeException('Venta abierta no encontrada.');$p=json_decode((string)$r['payload_json'],true)?:[];unset($r['payload_json']);$r['items']=$p['items']??[];return $r;}
 public function complete(int $businessId,string $publicId):void{$q=$this->db->prepare("UPDATE open_sale_tabs SET status='completed',updated_at=NOW() WHERE business_id=? AND public_id=? AND status='open'");$q->execute([$businessId,$publicId]);}
}