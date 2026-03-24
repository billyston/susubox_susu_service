<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\PaymentInstruction;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionShowService
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @param PaymentInstruction $paymentInstruction
     * @return PaymentInstruction
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
        PaymentInstruction $paymentInstruction,
    ): PaymentInstruction {
        try {
            // Ensure account belongs to customer
            $paymentInstruction = PaymentInstruction::query()
                ->where('id', $paymentInstruction->id)
                ->where('account_id', $account->id)
                ->whereHas('account.customers', function ($query) use ($customer) {
                    $query->where('customers.id', $customer->id);
                })
                ->first();

            // (Guard): Throw UnauthorisedAccessException if $paymentInstruction fails
            if (! $paymentInstruction) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this payment instruction.'
                );
            }

            // Return the PaymentInstruction resource
            return $paymentInstruction;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentInstructionShowService', [
                'customer' => $customer,
                'account' => $account,
                'payment_instruction' => $paymentInstruction,
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
