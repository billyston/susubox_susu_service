<?php

declare(strict_types=1);

namespace App\Services\Shared\Data\Account;

use App\Domain\Account\Models\Account;

final class AccountData
{
    public static function toArray(
        Account $account,
    ): array {
        // Prepare and return the data
        return [
            // Account main data
            'type' => 'Account',
            'resource_id' => $account->resource_id,
            'attributes' => [
                'account_name' => $account->account_name,
                'account_number' => $account->account_number,
                'susu_amount' => $account->susu_amount->getAmount(),
                'initial_deposit' => $account->initial_deposit->getAmount(),
                'charges' => '0.00',
                'total' => $account->initial_deposit->getAmount(),
                'frequency' => $account->frequency ? $account->frequency->code : null,
                'start_date' => $account->start_date,
                'end_date' => $account->end_date,
                'date_created' => $account->created_at,
            ],
        ];
    }
}
