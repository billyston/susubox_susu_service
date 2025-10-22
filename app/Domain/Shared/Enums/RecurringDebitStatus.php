<?php

namespace App\Domain\Shared\Enums;

enum RecurringDebitStatus: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case PAUSED = 'paused';
    case STOPPED = 'stopped';
}
