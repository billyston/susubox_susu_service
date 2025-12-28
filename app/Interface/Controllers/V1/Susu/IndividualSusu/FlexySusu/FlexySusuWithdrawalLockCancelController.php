<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuWithdrawalLockCancelAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuWithdrawalLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuWithdrawalLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountLock $accountLock
     * @param FlexySusuWithdrawalLockCancelRequest $flexySusuWithdrawalLockCancelRequest
     * @param FlexySusuWithdrawalLockCancelAction $flexySusuWithdrawalLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountLock $accountLock,
        FlexySusuWithdrawalLockCancelRequest $flexySusuWithdrawalLockCancelRequest,
        FlexySusuWithdrawalLockCancelAction $flexySusuWithdrawalLockCancelAction
    ): JsonResponse {
        // Execute the FlexySusuWithdrawalLockCancelAction and return the JsonResponse
        return $flexySusuWithdrawalLockCancelAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
            accountLock: $accountLock,
            request: $flexySusuWithdrawalLockCancelRequest->validated()
        );
    }
}
