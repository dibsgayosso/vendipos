<?php
declare(strict_types=1);
namespace Vendi\Purchases;
use PDO;use RuntimeException;
final class SupplierService{
 public function __construct(private PDO $db){}
 public function search(int $businessId,string $term=''):array{$q=$this->db->prepare("SELECT id,name,phone,email,tax_id FROM suppliers WHERE business_id=? AND name LIKE ? ORDER BY name LIMIT 50");$q->execute([$businessId,'%'.trim($term).'%']);return$q->fetchAll();}
 public function create(int $businessId,array $d):int{$name=trim((string)($d['name']??''));if($name==='')throw new RuntimeException('Nombre del proveedor obligatorio.');$q=$this->db->prepare("INSERT INTO suppliers(business_id,name,phone,email,tax_id) VALUES(?,?,?,?,?)");$q->execute([$businessId,$name,$d['phone']??null,$d['email']??null,$d['tax_id']??null]);return(int)$this->db->lastInsertId();}
}