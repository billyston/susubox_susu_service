<?php

namespace Domain\Susu\Enums\Account;

enum AccountStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case SUSPENDED = 'suspended';
}
