<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuWithdrawalLockApprovalAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuWithdrawalLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountLock $accountLock
     * @param BizSusuWithdrawalLockApprovalRequest $bizSusuWithdrawalLockApprovalRequest
     * @param BizSusuWithdrawalLockApprovalAction $bizSusuWithdrawalLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        AccountLock $accountLock,
        BizSusuWithdrawalLockApprovalRequest $bizSusuWithdrawalLockApprovalRequest,
        BizSusuWithdrawalLockApprovalAction $bizSusuWithdrawalLockApprovalAction
    ): JsonResponse {
        // Execute the BizSusuWithdrawalLockApprovalAction and return the JsonResponse
        return $bizSusuWithdrawalLockApprovalAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            accountLock: $accountLock,
        );
    }
}
