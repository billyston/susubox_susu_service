<?php

namespace App\Domain\Transaction\Enums;

enum WithdrawalType: string
{
    case PARTIAL = 'partial';
    case FULL = 'full';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
