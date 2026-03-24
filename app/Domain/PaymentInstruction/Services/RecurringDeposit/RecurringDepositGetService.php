<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\RecurringDeposit;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RecurringDepositGetService
{
    /**
     * @param Account $account
     * @param AccountCustomer $accountCustomer
     * @param string $status
     * @return RecurringDeposit
     * @throws SystemFailureException
     */
    public static function execute(
        Account $account,
        AccountCustomer $accountCustomer,
        string $status,
    ): RecurringDeposit {
        try {
            // Get the recurring deposit PaymentInstruction for the $account
            $initialPaymentInstruction = $account->recurringDeposits()
                ->where('account_customer_id', $accountCustomer->id)
                ->where('status', $status)
                ->latest()
                ->first();

            if (! $initialPaymentInstruction) {
                throw new ModelNotFoundException('The payment instruction was not found.');
            }

            return $initialPaymentInstruction;
        } catch (
            ModelNotFoundException $exception
        ) {
            throw $exception;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in RecurringDepositGetService', [
                'account' => $account,
                'account_customer' => $accountCustomer,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to fetch the transaction.',
            );
        }
    }
}
