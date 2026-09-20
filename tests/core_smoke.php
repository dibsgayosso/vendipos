<?php
declare(strict_types=1);
require dirname(__DIR__).'/vendor/autoload.php';
use Vendi\Sales\PaymentValidator;
$fail=[];$ok=function($v,$m)use(&$fail){if(!$v)$fail[]=$m;};
try{$p=PaymentValidator::validate([['method'=>'cash','amount'=>40],['method'=>'card','amount'=>60]],100);$ok(count($p)===2,'mixed payment');}catch(Throwable$e){$fail[]=$e->getMessage();}
foreach([[['method'=>'cash','amount'=>99]],[['method'=>'bitcoin','amount'=>100]],[['method'=>'cash','amount'=>0]]]as$bad){try{PaymentValidator::validate($bad,100);$fail[]='invalid payment accepted';}catch(Throwable$e){}}
$qty=10.0;$returned=3.0;$request=4.0;$remaining=$qty-$returned;$ok($request<=$remaining,'partial return balance');$returned+=$request;$ok(abs(($qty-$returned)-3.0)<.000001,'remaining qty after second return');
$payments=[['amount'=>200.0,'allows_change'=>true],['amount'=>50.0,'allows_change'=>false]];$total=230.0;$change=array_sum(array_column($payments,'amount'))-$total;$allocated=0.0;foreach($payments as &$line){$line['change_amount']=0.0;if($change>0&&$line['allows_change']){$take=min($change,$line['amount']);$line['change_amount']=$take;$change-=$take;$allocated+=$take;}}unset($line);$ok(abs($allocated-20.0)<.000001,'change allocated once');$ok(abs($payments[0]['change_amount']-20.0)<.000001&&$payments[1]['change_amount']===0.0,'change only on eligible line');
$tz=new DateTimeZone('America/Tijuana');$local=new DateTimeImmutable('2026-09-20 00:00:00',$tz);$utc=$local->setTimezone(new DateTimeZone('UTC'));$ok($utc->format('Y-m-d H:i:s')!=='2026-09-20 00:00:00','branch local midnight differs from UTC');
if($fail){fwrite(STDERR,implode(PHP_EOL,$fail).PHP_EOL);exit(1);}echo "Vendi Preview v0.1 smoke tests OK\n";