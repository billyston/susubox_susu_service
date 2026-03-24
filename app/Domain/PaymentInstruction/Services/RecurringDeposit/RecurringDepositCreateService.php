<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\RecurringDeposit;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\Frequency;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RecurringDepositCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        AccountCustomer $accountCustomer,
        PaymentInstruction $paymentInstruction,
        Frequency $frequency,
        Money $recurringAmount,
        Money $initialAmount,
        int $initialDepositFrequency,
        bool $rolloverEnabled,
    ): RecurringDeposit {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $accountCustomer,
                $paymentInstruction,
                $frequency,
                $recurringAmount,
                $initialAmount,
                $initialDepositFrequency,
                $rolloverEnabled
            ) {
                // Create the RecurringDeposits and return the resource
                return $account->recurringDeposits()->create([
                    'account_customer_id' => $accountCustomer->id,
                    'payment_instruction_id' => $paymentInstruction->id,
                    'frequency_id' => $frequency->id,
                    'recurring_amount' => $recurringAmount,
                    'initial_amount' => $initialAmount,
                    'initial_frequency' => $initialDepositFrequency,
                    'rollover_enabled' => $rolloverEnabled,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in RecurringDepositCreateService', [
                'account' => $account,
                'account_customer' => $accountCustomer,
                'payment_instruction' => $paymentInstruction,
                'frequency' => $frequency,
                'recurring_amount' => $recurringAmount,
                'initial_amount' => $initialAmount,
                'initial_deposit_frequency' => $initialDepositFrequency,
                'rollover_enabled' => $rolloverEnabled,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'code' => $throwable->getCode(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to create the recurring deposit.',
            );
        }
    }
}
