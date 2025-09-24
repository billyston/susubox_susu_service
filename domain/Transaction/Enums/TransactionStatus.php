<?php

namespace Domain\Transaction\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case SUSPENDED = 'suspended';
}
