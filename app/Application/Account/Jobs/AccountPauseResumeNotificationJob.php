<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs;

use App\Application\Account\DTOs\AccountPauseResponseDTO;
use App\Domain\Account\Services\AccountPauseByResourceIdService;
use App\Domain\Customer\Services\CustomerByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\Requests\Notification\NotificationRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountPauseResumeNotificationJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $customerResource
     * @param string $accountPauseResource
     */
    public function __construct(
        public readonly string $customerResource,
        public readonly string $accountPauseResource,
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
        CustomerByResourceIdService $customerByResourceIdService,
        AccountPauseByResourceIdService $accountPauseByResourceIdService,
        NotificationRequestHandler $dispatcher,
    ): void {
        // Execute the CustomerByResourceIdService and return the resource
        $customer = $customerByResourceIdService->execute(
            customerResource: $this->customerResource
        );

        // Execute the AccountPauseByResourceIdService and return the resource
        $accountPause = $accountPauseByResourceIdService->execute(
            accountPauseResource: $this->accountPauseResource
        );

        // Build the AccountPauseResponseDTO
        $responseDTO = AccountPauseResponseDTO::fromDomain(
            accountPause: $accountPause,
            account: $accountPause->pauseable->account,
            customer: $customer,
        );

        // Dispatch the AccountPauseNotificationRequestHandler to SusuBox services
        $dispatcher->sendToSusuBoxService(
            service: config('susubox.notification.name'),
            endpoint: 'account/susu/pause',
            data: $responseDTO->toArray(),
        );
    }
}
