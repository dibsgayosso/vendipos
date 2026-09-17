<?php
declare(strict_types=1);

namespace Vendi\Support;

use InvalidArgumentException;

final class Money
{
    public static function round(float $value): float
    {
        return round($value, 2, PHP_ROUND_HALF_UP);
    }

    public static function calculateLine(
        float $quantity,
        float $unitPrice,
        float $discountPercent,
        float $taxRate
    ): array {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('La cantidad debe ser mayor que cero.');
        }

        if ($unitPrice < 0 || $discountPercent < 0 || $discountPercent > 100 || $taxRate < 0) {
            throw new InvalidArgumentException('Parámetros monetarios inválidos.');
        }

        $gross = $quantity * $unitPrice;
        $discount = $gross * ($discountPercent / 100);
        $taxable = $gross - $discount;
        $tax = $taxable * $taxRate;

        return [
            'gross' => self::round($gross),
            'discount' => self::round($discount),
            'taxable' => self::round($taxable),
            'tax' => self::round($tax),
            'total' => self::round($taxable + $tax),
        ];
    }
}
