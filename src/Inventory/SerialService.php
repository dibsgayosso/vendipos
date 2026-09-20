<?php
declare(strict_types=1);
namespace Vendi\Inventory;
use PDO;use RuntimeException;
final class SerialService{
 public function __construct(private PDO $db){}
 public function receive(int $businessId,int $branchId,int $productId,string $serial,?int $lotId=null,?int $purchaseId=null):int{$serial=trim($serial);if($serial==='')throw new RuntimeException('Número de serie obligatorio.');$q=$this->db->prepare("INSERT INTO inventory_serials(business_id,product_id,branch_id,serial_number,lot_id,purchase_id) VALUES(?,?,?,?,?,?)");$q->execute([$businessId,$productId,$branchId,$serial,$lotId,$purchaseId]);return(int)$this->db->lastInsertId();}
 public function available(int $businessId,int $branchId,int $productId):array{$q=$this->db->prepare("SELECT id,serial_number,lot_id FROM inventory_serials WHERE business_id=? AND branch_id=? AND product_id=? AND status='available' ORDER BY id");$q->execute([$businessId,$branchId,$productId]);return $q->fetchAll();}
}