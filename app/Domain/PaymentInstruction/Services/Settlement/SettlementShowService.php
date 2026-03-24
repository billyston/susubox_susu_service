<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\Settlement;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SettlementShowService
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @param Settlement $settlement
     * @return Settlement
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
        Settlement $settlement,
    ): Settlement {
        try {
            // Ensure account belongs to customer
            $settlement = Settlement::query()
                ->where('id', $settlement->id)
                ->where('account_id', $account->id)
                ->whereHas('account.customers', function ($query) use ($customer) {
                    $query->where('customers.id', $customer->id);
                })
                ->first();

            // (Guard): Throw UnauthorisedAccessException if $paymentInstruction fails
            if (! $settlement) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this settlement.'
                );
            }

            // Return the PaymentInstruction resource
            return $settlement;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in SettlementShowService', [
                'customer' => $customer,
                'account' => $account,
                'settlement' => $settlement,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the settlement resource.',
            );
        }
    }
}
