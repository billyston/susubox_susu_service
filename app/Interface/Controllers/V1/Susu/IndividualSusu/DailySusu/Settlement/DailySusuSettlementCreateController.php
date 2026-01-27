<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCreateRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

final class DailySusuSettlementCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuSettlementCreateRequest $dailySusuSettlementCreateRequest
     * @param DailySusuSettlementCreateAction $dailySusuSettlementCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     * @throws Throwable
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuSettlementCreateRequest $dailySusuSettlementCreateRequest,
        DailySusuSettlementCreateAction $dailySusuSettlementCreateAction
    ): JsonResponse {
        // Execute the DailySusuSettlementCreateAction and return the JsonResponse
        return $dailySusuSettlementCreateAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            request: $dailySusuSettlementCreateRequest->validated()
        );
    }
}
