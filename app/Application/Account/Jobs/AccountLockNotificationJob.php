<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs;

use App\Application\Account\DTOs\AccountLockResponseDTO;
use App\Domain\Account\Services\AccountLockByResourceIdService;
use App\Domain\Customer\Services\CustomerByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\Requests\Notification\NotificationRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountLockNotificationJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $customerResource
     * @param string $accountLockResource
     */
    public function __construct(
        public readonly string $customerResource,
        public readonly string $accountLockResource,
    ) {
        // ...
    }

    /**
     * @param CustomerByResourceIdService $customerByResourceIdService
     * @param AccountLockByResourceIdService $accountLockByResourceIdService
     * @param NotificationRequestHandler $dispatcher
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        CustomerByResourceIdService $customerByResourceIdService,
        AccountLockByResourceIdService $accountLockByResourceIdService,
        NotificationRequestHandler $dispatcher,
    ): void {
        // Execute the CustomerByResourceIdService and return the resource
        $customer = $customerByResourceIdService->execute(
            customerResource: $this->customerResource
        );

        // Execute the AccountLockByResourceIdService and return the resource
        $accountLock = $accountLockByResourceIdService->execute(
            accountLockResource: $this->accountLockResource
        );

        // Build the AccountLockResponseDTO
        $responseDTO = AccountLockResponseDTO::fromDomain(
            accountLock: $accountLock,
            account: $accountLock->lockable->account,
            customer: $customer,
        );

        // Dispatch the AccountLockNotificationRequestHandler to SusuBox services
        $dispatcher->sendToSusuBoxService(
            service: config('susubox.notification.name'),
            endpoint: 'account/susu/lock',
            data: $responseDTO->toArray(),
        );
    }
}
