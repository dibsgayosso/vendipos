<?php
declare(strict_types=1);
require __DIR__.'/../src/autoload.php';use Vendi\Database\Connection;
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
[$script,$email,$password,$name]=array_pad($argv,4,'');if(!$email||strlen($password)<10){fwrite(STDERR,"Uso: php bin/create-owner.php correo password \"Nombre\"\nPassword mínimo 10 caracteres.\n");exit(1);}
$db=Connection::get();$db->beginTransaction();try{$db->exec("INSERT INTO businesses(name,status) VALUES('Vendi POS','active')");$b=(int)$db->lastInsertId();$q=$db->prepare("INSERT INTO branches(business_id,code,name,status,is_billable_addon,addon_status,activated_at) VALUES(?,'MATRIZ','Matriz','active',0,'included',NOW())");$q->execute([$b]);$br=(int)$db->lastInsertId();$q=$db->prepare("INSERT INTO users(business_id,default_branch_id,name,email,password_hash,role,status) VALUES(?,?,?,?,?,'owner','active')");$q->execute([$b,$br,$name?:'Propietario',mb_strtolower($email),password_hash($password,PASSWORD_DEFAULT)]);$db->commit();echo "Owner creado. Negocio ID: $b | Sucursal ID: $br\n";}catch(Throwable$e){$db->rollBack();fwrite(STDERR,$e->getMessage()."\n");exit(1);}
