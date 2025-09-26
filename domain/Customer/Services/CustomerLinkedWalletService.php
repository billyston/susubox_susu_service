<?php

declare(strict_types=1);

namespace Domain\Customer\Services;

use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use Domain\Customer\Models\Customer;
use Domain\Customer\Models\LinkedWallet;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerLinkedWalletService
{
    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     */
    public function execute(
        Customer $customer,
        string $wallet_resource_id
    ): LinkedWallet {
        try {
            return LinkedWallet::where([
                ['resource_id', '=', $wallet_resource_id],
                ['customer_id', '=', $customer->id],
                ['status', '=', 'active'],
            ])->firstOrFail();
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw new LinkedWalletNotFoundException(
                message: 'The linked wallet provided was not found.'
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerLinkedWalletService', [
                'customer' => $customer,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException;
        }
    }
}
