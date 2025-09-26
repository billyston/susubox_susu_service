<?php

declare(strict_types=1);

namespace Domain\Customer\Services;

use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Customer\Models\LinkedWallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerLinkedWalletsService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer
    ): Collection {
        try {
            return LinkedWallet::where(
                'status',
                'active',
            )->where(
                'customer_id',
                $customer->id,
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

            // Throw the SystemFailureException
            throw new SystemFailureException;
        }
    }
}
