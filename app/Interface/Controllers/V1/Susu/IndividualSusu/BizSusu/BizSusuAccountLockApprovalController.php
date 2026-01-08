<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuAccountLockApprovalAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuAccountLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountLock $accountLock
     * @param BizSusuAccountLockApprovalRequest $bizSusuAccountLockApprovalRequest
     * @param BizSusuAccountLockApprovalAction $bizSusuAccountLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        AccountLock $accountLock,
        BizSusuAccountLockApprovalRequest $bizSusuAccountLockApprovalRequest,
        BizSusuAccountLockApprovalAction $bizSusuAccountLockApprovalAction
    ): JsonResponse {
        // Execute the BizSusuAccountLockApprovalAction and return the JsonResponse
        return $bizSusuAccountLockApprovalAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            accountLock: $accountLock,
        );
    }
}
