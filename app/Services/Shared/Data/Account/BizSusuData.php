<?php

declare(strict_types=1);

namespace App\Services\Shared\Data\Account;

use App\Domain\Susu\Models\BizSusu;

final class BizSusuData
{
    public static function toArray(
        BizSusu $bizSusu,
    ): array {
        // Prepare and return the data
        return [
            // Account main data
            'data' => AccountData::toArray($bizSusu->account),

            // Included data
            'included' => [
                'service' => [
                    'type' => 'service',

                    'attributes' => [
                        'service' => 'susu',
                        'frequency' => $bizSusu->frequency->code,
                        'rollover_enabled' => $bizSusu->rollover_enabled,
                        'product_code' => $bizSusu->account->scheme->code,
                    ],
                ],
                'linked_wallet' => LinkedWalletData::toArray($bizSusu->account->wallets),
            ],
        ];
    }
}
