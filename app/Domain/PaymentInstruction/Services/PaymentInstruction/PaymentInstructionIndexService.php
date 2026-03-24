<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\PaymentInstruction;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionIndexService
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @return Collection
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
    ): Collection {
        try {
            // Ensure account belongs to customer
            $belongsToCustomer = $account->customers()
                ->where('customers.id', $customer->id)
                ->exists();

            // (Guard): Throw UnauthorisedAccessException if $belongsToCustomer fails
            if (! $belongsToCustomer) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to this action.'
                );
            }

            // Execute the database transaction
            return PaymentInstruction::query()
                ->where('account_id', $account->id)
                ->orderByDesc('created_at')
                ->get();
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentInstructionIndexService', [
                'customer' => $customer,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the payment instruction.',
            );
        }
    }
}
