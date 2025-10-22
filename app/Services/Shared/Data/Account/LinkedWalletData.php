<?php

declare(strict_types=1);

namespace App\Services\Shared\Data\Account;

use App\Domain\Customer\Models\LinkedWallet;
use Illuminate\Database\Eloquent\Collection;

final class LinkedWalletData
{
    public static function toArray(
        LinkedWallet|Collection $linkedWallets
    ): array {
        // Handle a collection
        if ($linkedWallets instanceof Collection) {
            // If the collection has only one item, return it as a single array
            if ($linkedWallets->count() === 1) {
                return self::formatWallet($linkedWallets->first());
            }

            // Otherwise, return an array of formatted wallets
            return $linkedWallets->map(function (LinkedWallet $wallet) {
                return self::formatWallet($wallet);
            })->values()->toArray();
        }

        // Handle a single LinkedWallet instance
        return self::formatWallet($linkedWallets);
    }

    private static function formatWallet(
        LinkedWallet $wallet
    ): array {
        return [
            'type' => 'LinkedWallet',
            'attributes' => [
                'wallet_name' => $wallet->wallet_name,
                'wallet_number' => $wallet->wallet_number,
                'wallet_network' => $wallet->network_code,
            ],
        ];
    }
}
