<?php

namespace Domain\Shared\Enums;

enum SusuSchemeStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
}
