<?php

namespace Domain\Shared\Enums;

enum WithdrawalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case SUCCESS = 'success';

    // To manage and allow account withdrawal or not
    case LOCKED = 'locked';
    case ACTIVE = 'active';
}
