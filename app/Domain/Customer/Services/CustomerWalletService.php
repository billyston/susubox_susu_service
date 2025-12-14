<?php

declare(strict_types=1);

namespace App\Domain\Customer\Services;

use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerWalletService
{
    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     */
    public function execute(
        Customer $customer,
        string $wallet_resource_id,
    ): Wallet {
        try {
            return Wallet::where([
                ['resource_id', '=', $wallet_resource_id],
                ['customer_id', '=', $customer->id],
                ['status', '=', 'active'],
            ])->firstOrFail();
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw new LinkedWalletNotFoundException(
                message: 'The wallet provided was not found.'
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerWalletService', [
                'customer' => $customer,
                'wallet_resource_id' => $wallet_resource_id,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to fetch the linked wallet.',
            );
        }
    }
}
