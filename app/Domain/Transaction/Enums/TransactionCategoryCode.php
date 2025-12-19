<?php

namespace App\Domain\Transaction\Enums;

enum TransactionCategoryCode: string
{
    case RECURRING_DEBIT_CODE = 'TXN-01';
    case DIRECT_DEBIT_CODE = 'TXN-02';
    case SETTLEMENT_CODE = 'TXN-03';
    case WITHDRAWAL_CODE = 'TXN-04';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
