<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountCycle;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCycleDefinition;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountCycleDefinitionCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        int $cycleLength,
        int $expectedFrequencies,
        int $payoutFrequencies,
        int $commissionFrequencies,
        Money $expectedCycleAmount,
        Money $expectedPayoutAmount,
        Money $commissionAmount,
    ): AccountCycleDefinition {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $cycleLength,
                $expectedFrequencies,
                $payoutFrequencies,
                $commissionFrequencies,
                $expectedCycleAmount,
                $expectedPayoutAmount,
                $commissionAmount
            ) {
                // Linked wallet, and customer to the account
                return $account->accountCycleDefinition()->create([
                    'cycle_length' => $cycleLength,
                    'expected_frequencies' => $expectedFrequencies,
                    'payout_frequencies' => $payoutFrequencies,
                    'commission_frequencies' => $commissionFrequencies,
                    'expected_cycle_amount' => $expectedCycleAmount,
                    'expected_payout_amount' => $expectedPayoutAmount,
                    'commission_amount' => $commissionAmount,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountCycleDefinitionCreateService', [
                'account' => $account,
                'cycle_length' => $cycleLength,
                'expected_frequencies' => $expectedFrequencies,
                'payout_frequencies' => $payoutFrequencies,
                'commission_frequencies' => $commissionFrequencies,
                'expected_cycle_amount' => $expectedCycleAmount,
                'expected_payout_amount' => $expectedPayoutAmount,
                'commission_amount' => $commissionAmount,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to the account cycle definition.',
            );
        }
    }
}
