<?php

declare(strict_types=1);

namespace App\Domain\Customer\Services;

use App\Application\Customer\DTOs\CustomerWalletCreateRequestDTO;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerWalletCreateService
{
    /**
     * @param Customer $customer
     * @param CustomerWalletCreateRequestDTO $requestDTO
     * @return Wallet
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        CustomerWalletCreateRequestDTO $requestDTO
    ): Wallet {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $customer,
                    $requestDTO
                ) {
                    // Get the WalletData for the Customer (if it exists)
                    $linked_wallet = Wallet::query()->firstOrNew([
                        'wallet_number' => $requestDTO->walletNumber,
                        'customer_id' => $customer->id,
                    ]);

                    return Wallet::updateOrCreate([
                        'wallet_number' => $requestDTO->walletNumber,
                        'customer_id' => $customer->id,
                    ], [
                        'resource_id' => $requestDTO->resourceID,
                        'customer_id' => $customer->id,
                        'wallet_name' => $requestDTO->walletName ?? $linked_wallet->wallet_name,
                        'wallet_number' => $requestDTO->walletNumber ?? $linked_wallet->wallet_number,
                        'network_code' => $requestDTO->networkCode ?? $linked_wallet->network_code,
                    ])->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerWalletCreateService', [
                'customer' => $customer,
                'data' => $requestDTO,
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
