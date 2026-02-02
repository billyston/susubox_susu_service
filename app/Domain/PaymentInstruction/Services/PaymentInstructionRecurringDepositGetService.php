<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services;

use App\Domain\Account\Models\Account;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionRecurringDepositGetService
{
    /**
     * @param Account $account
     * @param string $status
     * @return PaymentInstruction
     * @throws SystemFailureException
     */
    public static function execute(
        Account $account,
        string $status,
    ): PaymentInstruction {
        try {
            // Run the query inside a database transaction
            $initialPaymentInstruction = $account->payments()
                ->where('extra_data->is_initial_deposit', true)
                ->where('status', $status)
                ->latest()
                ->first();

            // Throw exception if no record is found
            if (! $initialPaymentInstruction) {
                throw new ModelNotFoundException('The payment instruction was not found.');
            }

            // Return the record if found
            return $initialPaymentInstruction;
        } catch (
            ModelNotFoundException $exception
        ) {
            throw $exception;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentInstructionRecurringDepositGetService', [
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to fetch the transaction.',
            );
        }
    }
}
