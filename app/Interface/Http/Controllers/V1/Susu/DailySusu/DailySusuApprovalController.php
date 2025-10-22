<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\DailySusu;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\DailySusu\DailySusuApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuApprovalRequest $dailySusuApprovalRequest,
        DailySusuApprovalAction $dailySusuApprovalAction
    ): JsonResponse {
        // Execute the DailySusuApprovalAction and return the JsonResponse
        return $dailySusuApprovalAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            dailySusuApprovalRequest: $dailySusuApprovalRequest
        );
    }
}
