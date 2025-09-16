<?php

declare(strict_types=1);

namespace Domain\Customer\Services;

use App\Exceptions\Common\SystemFailureExec;
use Domain\Customer\Models\Customer;
use Domain\Customer\Models\LinkedWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

final class CustomerLinkedWalletCreateService
{
    /**
     * @throws SystemFailureExec
     */
    public static function execute(
        Customer $customer,
        array $data
    ): void {
        try {
            // Execute the database transaction
            DB::transaction(
                function () use (
                    $customer,
                    $data
                ) {
                    // Get the LinkedWallet for the Customer (if it exists)
                    $linked_wallet = LinkedWallet::firstOrNew([
                        'wallet_number' => $data['wallet_number'],
                        'customer_id' => $customer->id,
                    ]);

                    $linked_wallet->fill([
                        'resource_id' => $linked_wallet->exists ? $linked_wallet->resource_id : (string) Str::uuid(),
                        'customer_id' => $customer->id,
                        'wallet_name' => $data['wallet_name'] ?? $linked_wallet->wallet_name,
                        'wallet_number' => $data['wallet_number'] ?? $linked_wallet->wallet_number,
                        'network_code' => $data['network_code'] ?? $linked_wallet->network_code,
                    ]);

                    $linked_wallet->save();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerLinkedWalletCreateService', [
                'request' => $data,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureExec
            throw new SystemFailureExec;
        }
    }
}
