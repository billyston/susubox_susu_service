<?php

declare(strict_types=1);

namespace App\Services\Shared\Data\Account;

use App\Domain\Susu\Models\DailySusu;

final class DailySusuData
{
    public static function toArray(
        DailySusu $dailySusu,
    ): array {
        // Prepare and return the data
        return [
            // Account main data
            'data' => AccountData::toArray($dailySusu->account),

            // Included data
            'included' => [
                'service' => [
                    'type' => 'service',

                    'attributes' => [
                        'service' => 'susu',
                        'frequency' => $dailySusu->frequency->code,
                        'rollover_enabled' => $dailySusu->rollover_enabled,
                        'product_code' => $dailySusu->account->scheme->code,
                    ],
                ],
                'linked_wallet' => LinkedWalletData::toArray($dailySusu->account->wallets),
            ],
        ];
    }
}
