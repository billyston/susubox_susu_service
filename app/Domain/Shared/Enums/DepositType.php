<?php

namespace App\Domain\Shared\Enums;

enum DepositType: string
{
    case FREQUENCY = 'frequency';
    case AMOUNT = 'amount';
}
