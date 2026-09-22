<?php
declare(strict_types=1);
namespace Vendi\Inventory;
use PDO;use RuntimeException;
final class PresentationService{
 public function __construct(private PDO $db){}
 public function list(int $businessId,int $productId):array{$q=$this->db->prepare("SELECT * FROM product_presentations WHERE business_id=? AND product_id=? AND active=1 ORDER BY is_base DESC,sort_order,name");$q->execute([$businessId,$productId]);return $q->fetchAll();}
 public function toBase(int $businessId,int $presentationId,float $qty):float{if($qty<=0)throw new RuntimeException('Cantidad inválida.');$q=$this->db->prepare("SELECT conversion_to_base FROM product_presentations WHERE id=? AND business_id=? AND active=1");$q->execute([$presentationId,$businessId]);$f=$q->fetchColumn();if($f===false)throw new RuntimeException('Presentación inválida.');return round($qty*(float)$f,6);}
}