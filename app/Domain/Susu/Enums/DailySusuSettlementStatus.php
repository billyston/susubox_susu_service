<?php

namespace App\Domain\Susu\Enums;

enum DailySusuSettlementStatus: string
{
    // Handle settlement status
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case TERMINATED = 'terminated';

    // Handles settlement action (mostly for zero-out-settlement)
    case RESTART = 'restart';

    // To manage and allow account settlement or not
    case LOCKED = 'locked';
    case ACTIVE = 'active';
}
