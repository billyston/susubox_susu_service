<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuAccountPauseCancelAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountPauseCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountPauseCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountPause $accountPause
     * @param DailySusuAccountPauseCancelRequest $dailySusuAccountPauseCancelRequest
     * @param DailySusuAccountPauseCancelAction $dailySusuAccountPauseCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountPause $accountPause,
        DailySusuAccountPauseCancelRequest $dailySusuAccountPauseCancelRequest,
        DailySusuAccountPauseCancelAction $dailySusuAccountPauseCancelAction
    ): JsonResponse {
        // Execute the DailySusuSettlementLockCancelAction and return the JsonResponse
        return $dailySusuAccountPauseCancelAction->execute(
            accountPause: $accountPause,
        );
    }
}
