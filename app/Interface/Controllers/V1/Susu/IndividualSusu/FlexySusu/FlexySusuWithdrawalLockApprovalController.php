<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuWithdrawalLockApprovalAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuWithdrawalLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuWithdrawalLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountLock $accountLock
     * @param FlexySusuWithdrawalLockApprovalRequest $flexySusuWithdrawalLockApprovalRequest
     * @param FlexySusuWithdrawalLockApprovalAction $flexySusuWithdrawalLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountLock $accountLock,
        FlexySusuWithdrawalLockApprovalRequest $flexySusuWithdrawalLockApprovalRequest,
        FlexySusuWithdrawalLockApprovalAction $flexySusuWithdrawalLockApprovalAction
    ): JsonResponse {
        // Execute the FlexySusuWithdrawalLockApprovalAction and return the JsonResponse
        return $flexySusuWithdrawalLockApprovalAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
            accountLock: $accountLock,
        );
    }
}
