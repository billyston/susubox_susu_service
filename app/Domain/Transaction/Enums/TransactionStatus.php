<?php

namespace App\Domain\Transaction\Enums;

enum TransactionStatus: string
{
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case REVERSED = 'reversed';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    case PENDING = 'pending';
    case APPROVED = 'approved';

    case FREQUENCY = 'frequency';
    case AMOUNT = 'amount';

    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
