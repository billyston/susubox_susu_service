<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs;

use App\Domain\Account\Services\AccountPauseByResourceIdService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Services\RecurringDebitStatusUpdateService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountPauseResumePostActionsJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $accountPauseResource
     */
    public function __construct(
        public readonly string $accountPauseResource,
    ) {
        // ...
    }

    /**
     * @param AccountPauseByResourceIdService $accountPauseByResourceIdService
     * @param RecurringDebitStatusUpdateService $recurringDebitStatusUpdateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountPauseByResourceIdService $accountPauseByResourceIdService,
        RecurringDebitStatusUpdateService $recurringDebitStatusUpdateService
    ): void {
        // Execute the AccountPauseByResourceIdService and return the resource
        $accountPause = $accountPauseByResourceIdService->execute(
            accountPauseResource: $this->accountPauseResource
        );

        // Get the pauseable_type
        $pauseable = $accountPause->pauseable;

        // Execute the RecurringDebitStatusUpdateService and return the resource
        $recurringDebitStatusUpdateService->execute(
            model: $pauseable,
            status: Statuses::ACTIVE->value,
        );
    }
}
