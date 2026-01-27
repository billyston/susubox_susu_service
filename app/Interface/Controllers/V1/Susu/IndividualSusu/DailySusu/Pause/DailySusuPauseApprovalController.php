<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Pause\DailySusuPauseApprovalAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Pause\DailySusuPauseApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuPauseApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountPause $accountPause
     * @param DailySusuPauseApprovalRequest $dailySusuPauseApprovalRequest
     * @param DailySusuPauseApprovalAction $dailySusuPauseApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountPause $accountPause,
        DailySusuPauseApprovalRequest $dailySusuPauseApprovalRequest,
        DailySusuPauseApprovalAction $dailySusuPauseApprovalAction
    ): JsonResponse {
        // Execute the DailySusuPauseApprovalAction and return the JsonResponse
        return $dailySusuPauseApprovalAction->execute(
            accountPause: $accountPause,
        );
    }
}
