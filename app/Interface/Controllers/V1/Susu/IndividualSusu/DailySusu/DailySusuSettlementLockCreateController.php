<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuSettlementLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementLockCreateRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuSettlementLockCreateRequest $dailySusuSettlementLockCreateRequest
     * @param DailySusuSettlementLockCreateAction $dailySusuSettlementLockCreateAction
     * @return JsonResponse
     * @throws MoneyMismatchException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
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
