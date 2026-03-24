<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\Settlement;

use App\Domain\Account\Models\Account;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\Shared\Enums\Initiators;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SettlementCreateService
{
    /**
     * @param Account $account
     * @param PaymentInstruction $paymentInstruction
     * @param Initiators $initiator
     * @param string $settlementScope
     * @param Money $principalAmount
     * @param Money $chargeAmount
     * @param Money $totalAmount
     * @return Settlement
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        PaymentInstruction $paymentInstruction,
        Initiators $initiator,
        string $settlementScope,
        Money $principalAmount,
        Money $chargeAmount,
        Money $totalAmount
    ): Settlement {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $paymentInstruction,
                $initiator,
                $settlementScope,
                $principalAmount,
                $chargeAmount,
                $totalAmount
            ) {
                // Create the PaymentInstruction and return the resource
                return $account->settlements()->create([
                    'payment_instruction_id' => $paymentInstruction->id,
                    'initiated_by' => $initiator,
                    'settlement_scope' => $settlementScope,
                    'principal_amount' => $principalAmount,
                    'charge_amount' => $chargeAmount,
                    'total_amount' => $totalAmount,
                    'status' => Statuses::PENDING->value,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in SettlementCreateService', [
                'account' => $account,
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
                message: 'There was an error while trying to create the settlement.',
            );
        }
    }
}
