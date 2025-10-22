<?php

declare(strict_types=1);

namespace App\Services\Shared\Data\Account;

use App\Domain\Susu\Models\FlexySusu;

final class FlexySusuData
{
    public static function toArray(
        FlexySusu $flexySusu,
    ): array {
        // Prepare and return the data
        return [
            // Account main data
            'data' => AccountData::toArray($flexySusu->account),

            // Included data
            'included' => [
                'service' => [
                    'type' => 'service',

                    'attributes' => [
                        'service' => 'susu',
                        'rollover_enabled' => $flexySusu->rollover_enabled,
                        'product_code' => $flexySusu->account->scheme->code,
                    ],
                ],
                'linked_wallet' => LinkedWalletData::toArray($flexySusu->account->wallets),
            ],
        ];
    }
}
