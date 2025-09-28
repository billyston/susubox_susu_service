<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\DailySusu\DailySusuApprovalRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\DailySusu\DailySusuApprovalAction;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        DailySusuApprovalRequest $dailySusuApprovalRequest,
        DailySusuApprovalAction $dailySusuApprovalAction
    ): JsonResponse {
        // Execute the DailySusuApprovalAction and return the JsonResponse
        return $dailySusuApprovalAction->execute(
            customer: $customer,
            account: $account,
            dailySusuApprovalRequest: $dailySusuApprovalRequest
        );
    }
}
