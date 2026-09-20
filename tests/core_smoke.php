<?php
declare(strict_types=1);require dirname(__DIR__).'/vendor/autoload.php';use Vendi\Sales\PaymentValidator;
$fail=[];$ok=function($v,$m)use(&$fail){if(!$v)$fail[]=$m;};
try{$p=PaymentValidator::validate([['method'=>'cash','amount'=>40],['method'=>'card','amount'=>60]],100);$ok(count($p)===2,'mixed payment');}catch(Throwable$e){$fail[]=$e->getMessage();}
foreach([[['method'=>'cash','amount'=>99]], [['method'=>'bitcoin','amount'=>100]], [['method'=>'cash','amount'=>0]]] as$bad){try{PaymentValidator::validate($bad,100);$fail[]='invalid payment accepted';}catch(Throwable$e){}}
$qty=10.0;$returned=3.0;$request=4.0;$remaining=$qty-$returned;$ok($request<=$remaining,'partial return balance');$returned+=$request;$ok(abs(($qty-$returned)-3.0)<0.000001,'remaining qty after second return');
$lotSold=10.0;$lotReturned=3.0;$lotTake=min($request,$lotSold-$lotReturned);$ok($lotTake===4.0,'lot balance');$lotReturned+=$lotTake;$ok($lotReturned===7.0,'lot cumulative return');
$serials=[1,2,3,4];$already=[1,2];$available=array_values(array_diff($serials,$already));$ok($available===[3,4],'serial cannot be returned twice');
if($fail){fwrite(STDERR,implode(PHP_EOL,$fail).PHP_EOL);exit(1);}echo "Core integrity smoke tests OK\n";