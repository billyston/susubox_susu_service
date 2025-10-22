<?php

namespace App\Domain\Shared\Enums;

enum DurationStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
}
