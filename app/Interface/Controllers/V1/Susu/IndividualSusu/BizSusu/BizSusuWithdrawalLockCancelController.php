<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuWithdrawalLockCancelAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuWithdrawalLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountLock $accountLock
     * @param BizSusuWithdrawalLockCancelRequest $bizSusuWithdrawalLockCancelRequest
     * @param BizSusuWithdrawalLockCancelAction $bizSusuWithdrawalLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        AccountLock $accountLock,
        BizSusuWithdrawalLockCancelRequest $bizSusuWithdrawalLockCancelRequest,
        BizSusuWithdrawalLockCancelAction $bizSusuWithdrawalLockCancelAction
    ): JsonResponse {
        // Execute the BizSusuWithdrawalLockCancelAction and return the JsonResponse
        return $bizSusuWithdrawalLockCancelAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            accountLock: $accountLock,
            request: $bizSusuWithdrawalLockCancelRequest->validated()
        );
    }
}
