<?php

declare(strict_types=1);

namespace App\Domain\Customer\Services;

use App\Application\Customer\DTOs\CustomerWalletDTO;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerWalletCreateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        CustomerWalletDTO $data
    ): Wallet {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $customer,
                    $data
                ) {
                    // Get the WalletData for the Customer (if it exists)
                    $linked_wallet = Wallet::query()->firstOrNew([
                        'wallet_number' => $data->wallet_number,
                        'customer_id' => $customer->id,
                    ]);

                    return Wallet::updateOrCreate([
                        'wallet_number' => $data->wallet_number,
                        'customer_id' => $customer->id,
                    ], [
                        'resource_id' => $data->resource_id,
                        'customer_id' => $customer->id,
                        'wallet_name' => $data->wallet_name ?? $linked_wallet->wallet_name,
                        'wallet_number' => $data->wallet_number ?? $linked_wallet->wallet_number,
                        'network_code' => $data->network_code ?? $linked_wallet->network_code,
                    ])->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerWalletCreateService', [
                'customer' => $customer,
                'data' => $data,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to create the link the wallet.',
            );
        }
    }
}
