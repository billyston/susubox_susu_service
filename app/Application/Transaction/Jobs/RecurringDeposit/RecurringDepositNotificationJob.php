<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs\RecurringDeposit;

use App\Application\Account\DTOs\AccountPause\AccountPauseResponseDTO;
use App\Domain\Account\Services\AccountPause\AccountPauseByResourceIdService;
use App\Domain\Customer\Services\CustomerByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\Requests\Notification\NotificationRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RecurringDepositNotificationJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param AccountPauseResponseDTO $responseDTO
     */
    public function __construct(
        public readonly AccountPauseResponseDTO $responseDTO,
    ) {
        // ...
    }

    /**
     * @param CustomerByResourceIdService $customerByResourceIdService
     * @param AccountPauseByResourceIdService $accountPauseByResourceIdService
     * @param NotificationRequestHandler $dispatcher
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        NotificationRequestHandler $dispatcher,
    ): void {
        // Set the endpoint

        // Dispatch the AccountPauseNotificationRequestHandler to SusuBox services
        $dispatcher->sendToSusuBoxService(
            service: config('susubox.notification.name'),
            endpoint: 'account/susu/pause',
            data: $this->responseDTO->toArray(),
        );
    }
}
