<?php

namespace App\Domain\Account\Enums;

enum AccountType: string
{
    case INDIVIDUAL = 'individual';
    case GROUP = 'group';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
