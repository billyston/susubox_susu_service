<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\Schedulers\RecurringDeposit;

use App\Application\PaymentInstruction\Jobs\RecurringDeposit\RecurringDepositResumeJob;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositExpireDueDateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RecurringDepositResumeScheduler implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
    ) {
        // ...
    }

    /**
     * @param RecurringDepositExpireDueDateService $recurringDepositExpireDueDateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        RecurringDepositExpireDueDateService $recurringDepositExpireDueDateService,
    ): void {
        // Execute the RecurringDepositExpireDueDateService and return the Collection
        $recurringDepositExpireDueDateService->execute(
            callback: function ($recurringDepositPauses) {
                // Loop the $recurringDepositPauses and dispatch the RecurringDepositResumeJob
                foreach ($recurringDepositPauses as $recurringDepositPause) {
                    RecurringDepositResumeJob::dispatch(
                        recurringDepositPauseResourceID: $recurringDepositPause->resource_id
                    );
                }
            }
        );
    }
}
