<?php

namespace App\Domain\Shared\Enums;

enum Currency: string
{
    case GHANA_CEDI = 'GHS';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
