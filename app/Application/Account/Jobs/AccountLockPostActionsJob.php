<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs;

use App\Domain\Account\Services\AccountLock\AccountLockByResourceIdService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Susu\Services\BizSusu\BizSusuWithdrawalStatusUpdateService;
use App\Domain\Susu\Services\FlexySusu\FlexySusuWithdrawalStatusUpdateService;
use App\Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuWithdrawalStatusUpdateService;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountSettlement\DailySusuSettlementStatusUpdateService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountLockPostActionsJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $accountLockResource
     */
    public function __construct(
        public readonly string $accountLockResource,
    ) {
        // ...
    }

    /**
     * @param AccountLockByResourceIdService $accountLockByResourceIdService
     * @param DailySusuSettlementStatusUpdateService $dailySusuSettlementStatusUpdateService
     * @param BizSusuWithdrawalStatusUpdateService $bizSusuWithdrawalStatusUpdateService
     * @param GoalGetterSusuWithdrawalStatusUpdateService $goalGetterSusuWithdrawalStatusUpdateService
     * @param FlexySusuWithdrawalStatusUpdateService $flexySusuWithdrawalStatusUpdateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountLockByResourceIdService $accountLockByResourceIdService,
        DailySusuSettlementStatusUpdateService $dailySusuSettlementStatusUpdateService,
        BizSusuWithdrawalStatusUpdateService $bizSusuWithdrawalStatusUpdateService,
        GoalGetterSusuWithdrawalStatusUpdateService $goalGetterSusuWithdrawalStatusUpdateService,
        FlexySusuWithdrawalStatusUpdateService $flexySusuWithdrawalStatusUpdateService
    ): void {
        // Execute the AccountLockByResourceIdService and return the resource
        $accountLock = $accountLockByResourceIdService->execute(
            accountLockResource: $this->accountLockResource
        );

        // Get the lockable_type
        $lockable = $accountLock->lockable;

        // Execute post-lock actions based on lockable stance
        match (true) {
            $lockable instanceof DailySusu => $dailySusuSettlementStatusUpdateService->execute(
                dailySusu: $lockable,
                status: Statuses::ACTIVE->value,
            ),
            $lockable instanceof BizSusu => $bizSusuWithdrawalStatusUpdateService->execute(
                bizSusu: $lockable,
                status: Statuses::ACTIVE->value,
            ),
            $lockable instanceof GoalGetterSusu => $goalGetterSusuWithdrawalStatusUpdateService->execute(
                goalGetterSusu: $lockable,
                status: Statuses::ACTIVE->value,
            ),
            $lockable instanceof FlexySusu => $flexySusuWithdrawalStatusUpdateService->execute(
                flexySusu: $lockable,
                status: Statuses::ACTIVE->value,
            ),
        };
    }
}
