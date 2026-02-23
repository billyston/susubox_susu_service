<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\ValueObjects\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCalculationVO;
use App\Domain\Account\Models\Account;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\PaymentInstruction\Models\SettlementCycle;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuAccountSettlementCreateService
{
    /**
     * @throws Throwable
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        iterable $accountCycles,
        PaymentInstruction $paymentInstruction,
        DailySusuSettlementCalculationVO $requestVO
    ): Settlement {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $accountCycles,
                $paymentInstruction,
                $requestVO
            ) {
                // Create a new Settlement
                $settlement = Settlement::create([
                    'account_id' => $account->id,
                    'payment_instruction_id' => $paymentInstruction->id,
                    'settlement_scope' => $requestVO->settlementScope,
                    'principal_amount' => $requestVO->principal,
                    'charge_amount' => $requestVO->charges,
                    'total_amount' => $requestVO->total,
                    'status' => $paymentInstruction->status,
                ]);

                // Loop through the $accountCycles and link the settlement to account_cycle
                foreach ($accountCycles as $cycle) {
                    SettlementCycle::create([
                        'account_settlement_id' => $settlement->id,
                        'account_cycle_id' => $cycle->id,
                    ]);
                }

                // Return the Settlement
                return $settlement;
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuSettlementService', [
                'account' => $account,
                'account_cycles' => $accountCycles,
                'payment_instruction' => $paymentInstruction,
                'request_vo' => $requestVO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was system failure while creating daily susu settlements.',
            );
        }
    }
}
