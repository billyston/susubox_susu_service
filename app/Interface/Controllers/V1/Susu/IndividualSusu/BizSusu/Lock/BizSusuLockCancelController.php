<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Lock;

use App\Application\Susu\Actions\IndividualSusu\BizSusu\Lock\BizSusuLockCancelAction;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\Lock\BizSusuLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountPayoutLock $accountLock
     * @param BizSusuLockCancelRequest $bizSusuLockCancelRequest
     * @param BizSusuLockCancelAction $bizSusuLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        AccountPayoutLock $accountLock,
        BizSusuLockCancelRequest $bizSusuLockCancelRequest,
        BizSusuLockCancelAction $bizSusuLockCancelAction
    ): JsonResponse {
        // Execute the BizSusuLockCancelAction and return the JsonResponse
        return $bizSusuLockCancelAction->execute(
            accountLock: $accountLock,
        );
    }
}
