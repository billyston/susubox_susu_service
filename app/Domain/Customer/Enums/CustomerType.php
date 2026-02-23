<?php

namespace App\Domain\Customer\Enums;

enum CustomerType: string
{
    case PRIMARY = 'primary';
    case MEMBER = 'member';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
