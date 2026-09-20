<?php
declare(strict_types=1);
require dirname(__DIR__).'/vendor/autoload.php';
use Vendi\Sales\PaymentValidator;
$failures=[];
$assert=function(bool$c,string$m)use(&$failures){if(!$c)$failures[]=$m;};
try{$p=PaymentValidator::validate([['method'=>'cash','amount'=>50],['method'=>'card','amount'=>50]],100);$assert(count($p)===2,'mixed payment rejected');}catch(Throwable$e){$failures[]='mixed payment threw '.$e->getMessage();}
try{PaymentValidator::validate([['method'=>'cash','amount'=>99]],100);$failures[]='insufficient payment accepted';}catch(Throwable$e){}
try{PaymentValidator::validate([['method'=>'bitcoin','amount'=>100]],100);$failures[]='unknown method accepted';}catch(Throwable$e){}
if($failures){fwrite(STDERR,implode(PHP_EOL,$failures).PHP_EOL);exit(1);}echo "Core smoke tests OK\n";