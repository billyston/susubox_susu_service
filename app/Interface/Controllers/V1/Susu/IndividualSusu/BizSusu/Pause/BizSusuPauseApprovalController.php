<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\BizSusu\Pause\BizSusuPauseApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\Pause\BizSusuPauseApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuPauseApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param RecurringDepositPause $accountPause
     * @param BizSusuPauseApprovalRequest $bizSusuPauseApprovalRequest
     * @param BizSusuPauseApprovalAction $bizSusuPauseApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        RecurringDepositPause $accountPause,
        BizSusuPauseApprovalRequest $bizSusuPauseApprovalRequest,
        BizSusuPauseApprovalAction $bizSusuPauseApprovalAction
    ): JsonResponse {
        // Execute the BizSusuPauseApprovalAction and return the JsonResponse
        return $bizSusuPauseApprovalAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            accountPause: $accountPause,
        );
    }
}
