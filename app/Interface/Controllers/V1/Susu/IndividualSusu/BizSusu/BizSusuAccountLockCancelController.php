<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuAccountLockCancelAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuAccountLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountLock $accountLock
     * @param BizSusuAccountLockCancelRequest $bizSusuAccountLockCancelRequest
     * @param BizSusuAccountLockCancelAction $bizSusuAccountLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        AccountLock $accountLock,
        BizSusuAccountLockCancelRequest $bizSusuAccountLockCancelRequest,
        BizSusuAccountLockCancelAction $bizSusuAccountLockCancelAction
    ): JsonResponse {
        // Execute the BizSusuAccountLockCancelAction and return the JsonResponse
        return $bizSusuAccountLockCancelAction->execute(
            accountLock: $accountLock,
        );
    }
}
