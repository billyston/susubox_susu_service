<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs\AccountPayoutLock;

use App\Application\Account\DTOs\AccountPayoutLock\AccountPayoutLockResponseDTO;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockByResourceIdService;
use App\Domain\Customer\Services\CustomerByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountPayoutLockNotificationJob implements ShouldQueue
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
     * @param AccountPayoutLockByResourceIdService $accountPayoutLockByResourceIdService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        CustomerByResourceIdService $customerByResourceIdService,
        AccountPayoutLockByResourceIdService $accountPayoutLockByResourceIdService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher,
    ): void {
        // Execute the CustomerByResourceIdService and return the resource
        $customer = $customerByResourceIdService->execute(
            customerResource: $this->customerResource
        );

        // Execute the AccountPayoutLockByResourceIdService and return the resource
        $accountPayoutLock = $accountPayoutLockByResourceIdService->execute(
            accountLockResource: $this->accountLockResource
        );

        // Build the AccountPayoutLockResponseDTO
        $responseDTO = AccountPayoutLockResponseDTO::fromDomain(
            accountPayoutLock: $accountPayoutLock,
            account: $accountPayoutLock->account,
            customer: $customer,
        );

        // Dispatch the SusuBoxServiceDispatcher to SusuBox services
        $susuBoxServiceDispatcher->send(
            service: config('susubox.notification.name'),
            endpoint: 'account/susu/lock',
            payload: $responseDTO->toArray(),
        );
    }
}
