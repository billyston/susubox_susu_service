<?php

namespace App\Domain\Shared\Enums;

enum Statuses: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case PAUSED = 'paused';
    case STOPPED = 'stopped';
    case SUSPENDED = 'suspended';
    case REMOVED = 'removed';
    case LOCKED = 'locked';
    case SUCCESS = 'success';
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';
    case REVERSED = 'reversed';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';
    case APPROVED = 'approved';
    case CLOSED = 'closed';
    case TERMINATED = 'terminated';
    case COMPLETED = 'completed';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
