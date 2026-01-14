<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\IndividualSusu\DailySusu;

use App\Application\Susu\DTOs\DailySusu\AccountCycle\DailySusuAccountCycleCompletedResponseDTO;
use App\Domain\Account\Services\AccountCycleByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\Requests\Notification\NotificationRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class DailySusuAccountCycleCompletedJob implements ShouldQueue
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
        NotificationRequestHandler $dispatcher
    ): void {
        // Execute the AccountCycleByResourceIdService and return the resource
        $accountCycle = $accountCycleByResourceIdService->execute(
            accountCycleResource: $this->accountCycleResourceID,
        );

        // Build the DailySusuAccountCycleCompletedResponseDTO
        $responseDTO = DailySusuAccountCycleCompletedResponseDTO::fromDomain(
            accountCycle: $accountCycle,
        );

        // Dispatch the AccountCycleCompletedNotificationRequestHandler to SusuBox services
        $dispatcher->sendToSusuBoxService(
            service: config('susubox.notification.name'),
            endpoint: 'account/susu/cycle-completed',
            data: $responseDTO->toArray(),
        );
    }
}
