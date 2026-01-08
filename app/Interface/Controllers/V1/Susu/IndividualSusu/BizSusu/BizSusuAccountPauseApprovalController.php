<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuAccountPauseApprovalAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountPauseApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuAccountPauseApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountPause $accountPause
     * @param BizSusuAccountPauseApprovalRequest $bizSusuAccountPauseApprovalRequest
     * @param BizSusuAccountPauseApprovalAction $bizSusuAccountPauseApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        AccountPause $accountPause,
        BizSusuAccountPauseApprovalRequest $bizSusuAccountPauseApprovalRequest,
        BizSusuAccountPauseApprovalAction $bizSusuAccountPauseApprovalAction
    ): JsonResponse {
        // Execute the BizSusuAccountPauseApprovalAction and return the JsonResponse
        return $bizSusuAccountPauseApprovalAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            accountPause: $accountPause,
        );
    }
}
