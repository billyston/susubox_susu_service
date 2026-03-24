<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\IndividualSusu\DailySusu\Cycle;

use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Cycle\DailySusuCycleCompletedResponseDTO;
use App\Domain\Account\Services\AccountCycle\AccountCycleByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class DailySusuCycleCompletedJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $accountCycleResourceID
     */
    public function __construct(
        private readonly string $accountCycleResourceID
    ) {
        // ...
    }

    /**
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function handle(
        AccountCycleByResourceIdService $accountCycleByResourceIdService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher
    ): void {
        // Execute the AccountCycleByResourceIdService and return the resource
        $accountCycle = $accountCycleByResourceIdService->execute(
            accountCycleResource: $this->accountCycleResourceID,
        );

        // Build the DailySusuCycleCompletedResponseDTO
        $responseDTO = DailySusuCycleCompletedResponseDTO::fromDomain(
            accountCycle: $accountCycle,
        );

        // Dispatch the SusuBoxServiceDispatcher to SusuBox services
        $susuBoxServiceDispatcher->send(
            service: config('susubox.notification.name'),
            endpoint: 'account/susu/cycle-completed',
            payload: $responseDTO->toArray(),
        );
    }
}
