<?php

namespace App\Domain\Transaction\Enums;

enum DepositType: string
{
    case FREQUENCY = 'frequency';
    case AMOUNT = 'amount';
}
