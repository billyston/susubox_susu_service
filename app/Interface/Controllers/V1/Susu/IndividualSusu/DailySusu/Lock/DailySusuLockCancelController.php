<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Lock;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Lock\DailySusuLockCancelAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Lock\DailySusuLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountLock $accountLock
     * @param DailySusuLockCancelRequest $dailySusuLockCancelRequest
     * @param DailySusuLockCancelAction $dailySusuLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountLock $accountLock,
        DailySusuLockCancelRequest $dailySusuLockCancelRequest,
        DailySusuLockCancelAction $dailySusuLockCancelAction
    ): JsonResponse {
        // Execute the DailySusuLockCancelAction and return the JsonResponse
        return $dailySusuLockCancelAction->execute(
            accountLock: $accountLock,
        );
    }
}
