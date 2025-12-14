<?php

namespace App\Domain\Transaction\Enums;

enum TransactionType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
}
