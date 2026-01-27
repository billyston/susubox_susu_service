<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Pause\DailySusuPauseCancelAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Pause\DailySusuPauseCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuPauseCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountPause $accountPause
     * @param DailySusuPauseCancelRequest $dailySusuPauseCancelRequest
     * @param DailySusuPauseCancelAction $dailySusuPauseCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountPause $accountPause,
        DailySusuPauseCancelRequest $dailySusuPauseCancelRequest,
        DailySusuPauseCancelAction $dailySusuPauseCancelAction
    ): JsonResponse {
        // Execute the DailySusuSettlementLockCancelAction and return the JsonResponse
        return $dailySusuPauseCancelAction->execute(
            accountPause: $accountPause,
        );
    }
}
