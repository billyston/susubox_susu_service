<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement\DailySusuSettlementLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\SettlementLock\DailySusuSettlementLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuSettlementLockCreateRequest $dailySusuSettlementLockCreateRequest
     * @param DailySusuSettlementLockCreateAction $dailySusuSettlementLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuSettlementLockCreateRequest $dailySusuSettlementLockCreateRequest,
        DailySusuSettlementLockCreateAction $dailySusuSettlementLockCreateAction
    ): JsonResponse {
        // Execute the DailySusuSettlementLockCreateAction and return the JsonResponse
        return $dailySusuSettlementLockCreateAction->execute(
            dailySusu: $dailySusu,
            request: $dailySusuSettlementLockCreateRequest->validated()
        );
    }
}
