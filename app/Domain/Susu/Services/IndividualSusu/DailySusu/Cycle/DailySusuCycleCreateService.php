<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Cycle;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Cycle\DailySusuCycleResponseDTO;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Account\Models\AccountCycleEntry;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCycleCreateService
{
    /**
     * @throws Throwable
     */
    public static function execute(
        DailySusuCycleResponseDTO $responseDTO
    ): AccountCycle {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $responseDTO
            ) {
                // Extract the main resources
                $dailySusu = $responseDTO->dailySusu;
                $account = $dailySusu->account;
                $accountCycleDefinition = $account->accountCycleDefinition;
                $paymentInstruction = $responseDTO->transaction->paymentInstruction;

                // Define key variables
                $remainingFrequencies = $responseDTO->frequency;
                $lastCycle = null;

                // Calculate amount per frequency
                $amountPerFrequency = $responseDTO->contributionAmount->dividedBy($responseDTO->frequency);

                // Loop through the $remainingFrequencies and create the AccountCycle
                while ($remainingFrequencies > 0) {
                    // Resolve or create active cycle
                    $accountCycle = AccountCycle::query()
                        ->where('account_id', $account->id)
                        ->where('account_cycle_definition_id', $accountCycleDefinition->id)
                        ->where('status', Statuses::ACTIVE->value)
                        ->lockForUpdate()
                        ->latest('cycle_number')
                        ->first();

                    // (Guard) Create new AccountCycle If there is none found
                    if (! $accountCycle) {
                        $cycleNumber = AccountCycle::query()
                            ->where('account_id', $account->id)
                            ->where('account_cycle_definition_id', $accountCycleDefinition->id)
                            ->max('cycle_number') ?? 0;

                        // Create new AccountCycle
                        $accountCycle = AccountCycle::create([
                            'account_id' => $account->id,
                            'account_cycle_definition_id' => $accountCycleDefinition->id,
                            'cycle_number' => $cycleNumber + 1,
                            'expected_frequencies' => $accountCycleDefinition->expected_frequencies,
                            'completed_frequencies' => 0,
                            'expected_amount' => $accountCycleDefinition->expected_cycle_amount,
                            'contributed_amount' => Money::of(0, 'GHS'),
                            'started_at' => now(),
                            'status' => Statuses::ACTIVE->value,
                        ]);
                    }

                    // Determine how much capacity remains in this cycle
                    $remainingCapacity = $accountCycle->expected_frequencies - $accountCycle->completed_frequencies;

                    // Determine how many frequencies can be applied
                    $appliedFrequencies = min($remainingFrequencies, $remainingCapacity);

                    // Determine the corresponding amount
                    $appliedAmount = $amountPerFrequency->multipliedBy($appliedFrequencies);

                    // Create the AccountCycleEntry
                    AccountCycleEntry::create([
                        'account_customer_id' => $paymentInstruction->accountCustomer->customer->id,
                        'account_cycle_id' => $accountCycle->id,
                        'payment_instruction_id' => $paymentInstruction->id,
                        'transaction_id' => $responseDTO->transaction->id,
                        'frequencies' => $appliedFrequencies,
                        'amount' => $appliedAmount,
                        'entry_type' => $responseDTO->entryType,
                        'posted_at' => now(),
                        'status' => $responseDTO->transaction->status,
                    ]);

                    // Update the AccountCycle counters */
                    $accountCycle->update([
                        'completed_frequencies' => $accountCycle->completed_frequencies + $appliedFrequencies,
                        'contributed_amount' => $accountCycle->contributed_amount->plus($appliedAmount),
                    ]);

                    // Check if the cycle is now complete
                    if ($accountCycle->completed_frequencies >= $accountCycle->expected_frequencies) {
                        // Mark cycle as completed
                        $accountCycle->update([
                            'status' => Statuses::COMPLETED->value,
                            'completed_at' => now(),
                        ]);

                        // Trigger completion event
                        event(new AccountCycleCompletedEvent(
                            accountCycleResourceId: $accountCycle->resource_id,
                        ));
                    }

                    // Reduce remaining frequencies
                    $remainingFrequencies -= $appliedFrequencies;

                    // Track last cycle touched
                    $lastCycle = $accountCycle;
                }

                // Return the final cycle
                return $lastCycle->refresh();
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuCycleService', [
                'response_dto' => $responseDTO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to cancel the create account process.',
            );
        }
    }
}
