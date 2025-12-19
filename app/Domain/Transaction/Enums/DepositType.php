<?php

namespace App\Domain\Transaction\Enums;

enum DepositType: string
{
    case FREQUENCY = 'frequency';
    case AMOUNT = 'amount';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
