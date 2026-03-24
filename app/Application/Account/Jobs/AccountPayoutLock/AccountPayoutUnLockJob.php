<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs\AccountPayoutLock;

use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockByResourceIdService;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Services\IndividualSusu\BizSusu\Withdrawal\BizSusuWithdrawalStatusUpdateService;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement\DailySusuSettlementStatusUpdateService;
use App\Domain\Susu\Services\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalStatusUpdateService;
use App\Domain\Susu\Services\IndividualSusu\GoalGetterSusu\Withdrawal\GoalGetterSusuWithdrawalStatusUpdateService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountPayoutUnLockJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $resourceID
    ) {
        // ...
    }

    /**
     * @param AccountPayoutLockByResourceIdService $accountPayoutLockByResourceIdService
     * @param AccountPayoutLockStatusUpdateService $accountPayoutLockStatusUpdateService
     * @param DailySusuSettlementStatusUpdateService $dailySusuSettlementStatusUpdateService
     * @param BizSusuWithdrawalStatusUpdateService $bizSusuWithdrawalStatusUpdateService
     * @param GoalGetterSusuWithdrawalStatusUpdateService $goalGetterSusuWithdrawalStatusUpdateService
     * @param FlexySusuWithdrawalStatusUpdateService $flexySusuWithdrawalStatusUpdateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountPayoutLockByResourceIdService $accountPayoutLockByResourceIdService,
        AccountPayoutLockStatusUpdateService $accountPayoutLockStatusUpdateService,
        DailySusuSettlementStatusUpdateService $dailySusuSettlementStatusUpdateService,
        BizSusuWithdrawalStatusUpdateService $bizSusuWithdrawalStatusUpdateService,
        GoalGetterSusuWithdrawalStatusUpdateService $goalGetterSusuWithdrawalStatusUpdateService,
        FlexySusuWithdrawalStatusUpdateService $flexySusuWithdrawalStatusUpdateService
    ): void {
        // Execute the AccountPayoutLockByResourceIdService and return the resource
        $accountPayoutLock = $accountPayoutLockByResourceIdService->execute(
            accountLockResource: $this->resourceID
        );

        // Execute the AccountPayoutLockStatusUpdateService
        $accountPayoutLockStatusUpdateService->execute(
            accountPayoutLock: $accountPayoutLock,
            status: Statuses::EXPIRED->value
        );

        // Get the account from $accountPayoutLock
        $account = $accountPayoutLock->account;

        // Resolve the susu type (scheme) and execute the handler
        match (true) {
            $account->dailySusu()->exists() => $dailySusuSettlementStatusUpdateService->execute(
                dailySusu: $account->dailySusu,
                status: Statuses::ACTIVE->value,
            ),
            $account->bizSusu()->exists() => $bizSusuWithdrawalStatusUpdateService->execute(
                bizSusu: $account->bizSusu,
                status: Statuses::ACTIVE->value,
            ),
            $account->goalGetterSusu()->exists() => $goalGetterSusuWithdrawalStatusUpdateService->execute(
                goalGetterSusu: $account->goalGetterSusu,
                status: Statuses::ACTIVE->value,
            ),
            $account->flexySusu()->exists() => $flexySusuWithdrawalStatusUpdateService->execute(
                flexySusu: $account->flexySusu,
                status: Statuses::ACTIVE->value,
            ),

//            $account->nkabomNhyiraSusu()->exists() => 'NkabomNhyiraSusu',
//            $account->dwadieboaSusu()->exists() => 'DwadieboaSusu',
//            $account->corporativeSusu()->exists() => 'CorporativeSusu',

            default => null
        };

        // Dispatch the AccountPayoutLockNotificationJob
        AccountPayoutLockNotificationJob::dispatch(
            customerResource: $account->accountCustomer->customer->resource_id,
            accountLockResource: $accountPayoutLock->resource_id
        );
    }
}
