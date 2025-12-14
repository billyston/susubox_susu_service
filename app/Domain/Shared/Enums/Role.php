<?php

namespace App\Domain\Shared\Enums;

enum Role: string
{
    case ADMIN = 'administrator';
    case ORGANIZER = 'organizer';
    case MEMBER = 'member';

    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
