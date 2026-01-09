<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\DailySusu;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use App\Application\Susu\DTOs\DailySusu\DailySusuCycleResponseDTO;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Account\Models\AccountCycleEntry;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCycleService
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
                // Define the key variables
                $remainingFrequencies = $responseDTO->frequency;
                $lastCycle = null;

                // Loop through the $remainingFrequencies and create the AccountCycle
                while ($remainingFrequencies > 0) {
                    /** Resolve or create active cycle */
                    $accountCycle = AccountCycle::query()
                        ->where('account_id', $responseDTO->dailySusu->account->id)
                        ->where('cycleable_type', DailySusu::class)
                        ->where('cycleable_id', $responseDTO->dailySusu->id)
                        ->where('status', Statuses::ACTIVE->value)
                        ->lockForUpdate()
                        ->latest('cycle_number')
                        ->first();

                    if (! $accountCycle) {
                        $cycleNumber = AccountCycle::query()
                            ->where('account_id', $responseDTO->dailySusu->account->id)
                            ->where('cycleable_type', DailySusu::class)
                            ->where('cycleable_id', $responseDTO->dailySusu->id)
                            ->max('cycle_number') ?? 0;

                        $accountCycle = AccountCycle::create([
                            'account_id' => $responseDTO->dailySusu->account->id,
                            'cycleable_type' => DailySusu::class,
                            'cycleable_id' => $responseDTO->dailySusu->id,
                            'cycle_number' => $cycleNumber + 1,
                            'expected_frequencies' => $responseDTO->dailySusu->cycleDefinition->expected_frequencies,
                            'completed_frequencies' => 0,
                            'expected_amount' => $responseDTO->dailySusu->cycleDefinition->expected_cycle_amount,
                            'contributed_amount' => Money::of(0, 'GHS'),
                            'started_at' => now(),
                            'status' => Statuses::ACTIVE->value,
                        ]);
                    }

                    /** How much can this cycle still take */
                    $remainingCapacity = $accountCycle->expected_frequencies - $accountCycle->completed_frequencies;
                    $appliedFrequencies = min($remainingFrequencies, $remainingCapacity);

                    /** Create cycle entry (split-aware) */
                    AccountCycleEntry::create([
                        'account_cycle_id' => $accountCycle->id,
                        'transaction_id' => $responseDTO->transaction->id,
                        'payment_instruction_id' => $responseDTO->transaction->payment_instruction_id,
                        'frequencies' => $appliedFrequencies,
                        'amount' => $responseDTO->contributionAmount,
                        'entry_type' => $responseDTO->entryType,
                        'posted_at' => now(),
                        'status' => $responseDTO->transaction->status,
                    ]);

                    /** Update counters */
                    $accountCycle->update([
                        'completed_frequencies' => $accountCycle->completed_frequencies + $appliedFrequencies,
                        'contributed_amount' => $accountCycle->contributed_amount->plus($responseDTO->contributionAmount),
                    ]);

                    /** Complete cycle if filled */
                    if ($accountCycle->completed_frequencies >= $accountCycle->expected_frequencies) {
                        // Update the AccountCycle status (completed)
                        $accountCycle->update([
                            'status' => Statuses::COMPLETED->value,
                            'completed_at' => now(),
                        ]);

                        // Trigger the AccountCycleCompletedEvent
                        event(new AccountCycleCompletedEvent(
                            accountCycleResourceId: $accountCycle->resource_id,
                        ));
                    }

                    $remainingFrequencies -= $appliedFrequencies;
                    $lastCycle = $accountCycle;
                }

                // Return the AccountCycle
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
