<?php

declare(strict_types=1);

namespace Domain\Customer\Services;

use App\Exceptions\Common\SystemFailureExec;
use Domain\Customer\Models\Customer;
use Domain\Customer\Models\LinkedWallet;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerLinkedWalletsService
{
    /**
     * @throws SystemFailureExec
     */
    public function execute(
        Customer $customer
    ) {
        try {
            return LinkedWallet::where(
                'status',
                'active',
            )->get();
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw $modelNotFoundException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerLinkedWalletsService', [
                'customer' => $customer,
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
