<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountSettlement;

use App\Application\Susu\Actions\DailySusu\AccountSettlement\DailySusuAccountSettlementCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\AccountSettlement\DailySusuAccountSettlementCreateRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

final class DailySusuAccountSettlementCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuAccountSettlementCreateRequest $dailySusuAccountSettlementCreateRequest
     * @param DailySusuAccountSettlementCreateAction $dailySusuAccountSettlementCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     * @throws Throwable
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuAccountSettlementCreateRequest $dailySusuAccountSettlementCreateRequest,
        DailySusuAccountSettlementCreateAction $dailySusuAccountSettlementCreateAction
    ): JsonResponse {
        // Execute the DailySusuAccountSettlementCreateAction and return the JsonResponse
        return $dailySusuAccountSettlementCreateAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            request: $dailySusuAccountSettlementCreateRequest->validated()
        );
    }
}
