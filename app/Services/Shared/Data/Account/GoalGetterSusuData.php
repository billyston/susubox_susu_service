<?php

declare(strict_types=1);

namespace App\Services\Shared\Data\Account;

use App\Domain\Susu\Models\GoalGetterSusu;

final class GoalGetterSusuData
{
    public static function toArray(
        GoalGetterSusu $goalGetterSusu,
    ): array {
        // Prepare and return the data
        return [
            // Account main data
            'data' => AccountData::toArray($goalGetterSusu->account),

            // Related data
            'relationships' => [
                'service' => [
                    'type' => 'service',

                    'attributes' => [
                        'service' => 'susu',
                        'frequency' => $goalGetterSusu->frequency->code,
                        'rollover_enabled' => $goalGetterSusu->rollover_enabled,
                        'product_code' => $goalGetterSusu->account->scheme->code,
                    ],
                ],
                'linked_wallet' => LinkedWalletData::toArray($goalGetterSusu->account->wallets),
            ],
        ];
    }
}
