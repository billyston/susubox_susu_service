<?php

namespace App\Domain\Shared\Enums;

enum Initiators: string
{
    case CUSTOMER = 'customer';
    case ADMINISTRATOR = 'administrator';
    case SYSTEM = 'system';
    case SCHEDULED = 'scheduled';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
