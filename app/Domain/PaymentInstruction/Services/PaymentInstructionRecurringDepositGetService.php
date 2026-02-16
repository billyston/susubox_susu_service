<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services;

use App\Domain\Account\Models\Account;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionRecurringDepositGetService
{
    /**
     * @param Account $account
     * @param string $status
     * @param Model $initiator
     * @return PaymentInstruction
     * @throws SystemFailureException
     */
    public static function execute(
        Account $account,
        Model $initiator,
        string $status,
    ): PaymentInstruction {
        try {
            // Get the recurring deposit PaymentInstruction for the $account
            $initialPaymentInstruction = $account->payments()
                ->where('extra_data->is_initial_deposit', true)
                ->where('status', $status)
                ->where('initiated_by_type', $initiator->getMorphClass())
                ->where('initiated_by_id', $initiator->getKey())
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
            Log::error('Exception in PaymentInstructionRecurringDepositGetService', [
                'account' => $account,
                'initiator' => $initiator,
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
