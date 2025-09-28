<?php

namespace Domain\Susu\Enums\Account;

enum AccountStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case SUSPENDED = 'suspended';

    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
