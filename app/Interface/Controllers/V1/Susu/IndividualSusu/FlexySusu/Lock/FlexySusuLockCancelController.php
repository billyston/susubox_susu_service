<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Lock;

use App\Application\Susu\Actions\IndividualSusu\FlexySusu\Lock\FlexySusuLockCancelAction;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\Lock\FlexySusuLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountPayoutLock $accountLock
     * @param FlexySusuLockCancelRequest $flexySusuLockCancelRequest
     * @param FlexySusuLockCancelAction $flexySusuLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountPayoutLock $accountLock,
        FlexySusuLockCancelRequest $flexySusuLockCancelRequest,
        FlexySusuLockCancelAction $flexySusuLockCancelAction
    ): JsonResponse {
        // Execute the FlexySusuLockCancelAction and return the JsonResponse
        return $flexySusuLockCancelAction->execute(
            accountLock: $accountLock,
        );
    }
}
