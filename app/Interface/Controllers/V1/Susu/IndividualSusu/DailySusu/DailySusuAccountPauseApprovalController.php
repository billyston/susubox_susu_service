<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuAccountPauseApprovalAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountPauseApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountPauseApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountPause $accountPause
     * @param DailySusuAccountPauseApprovalRequest $dailySusuAccountPauseApprovalRequest
     * @param DailySusuAccountPauseApprovalAction $dailySusuAccountPauseApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountPause $accountPause,
        DailySusuAccountPauseApprovalRequest $dailySusuAccountPauseApprovalRequest,
        DailySusuAccountPauseApprovalAction $dailySusuAccountPauseApprovalAction
    ): JsonResponse {
        // Execute the DailySusuAccountPauseApprovalAction and return the JsonResponse
        return $dailySusuAccountPauseApprovalAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountPause: $accountPause,
        );
    }
}
