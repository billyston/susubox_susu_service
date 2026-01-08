<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuAccountLockCancelAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountLock $accountLock
     * @param DailySusuAccountLockCancelRequest $dailySusuAccountLockCancelRequest
     * @param DailySusuAccountLockCancelAction $dailySusuAccountLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountLock $accountLock,
        DailySusuAccountLockCancelRequest $dailySusuAccountLockCancelRequest,
        DailySusuAccountLockCancelAction $dailySusuAccountLockCancelAction
    ): JsonResponse {
        // Execute the DailySusuAccountLockCancelAction and return the JsonResponse
        return $dailySusuAccountLockCancelAction->execute(
            accountLock: $accountLock,
        );
    }
}
