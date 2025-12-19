<?php

namespace App\Domain\Shared\Enums;

enum SusuType: string
{
    case INDIVIDUAL = 'individual';
    case GROUP = 'group';
    case CORPORATE = 'corporate';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
