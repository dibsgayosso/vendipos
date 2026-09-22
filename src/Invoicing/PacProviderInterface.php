<?php
declare(strict_types=1);namespace Vendi\Invoicing;
interface PacProviderInterface{public function stamp(array $invoice,array $items):array;public function cancel(string $uuid,string $rfc,string $reason,?string $replacementUuid=null):array;}