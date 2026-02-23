<?php

namespace App\Domain\Shared\Enums;

enum Initiators: string
{
    case ADMINISTRATOR = 'administrator';
    case SYSTEM = 'system';
    case CUSTOMER = 'customer';
    case MEMBER = 'member';
    case SCHEDULED = 'scheduled';
    case ORGANIZER = 'organizer';
    case SIGNATORY = 'signatory';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
