<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuWithdrawalCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Transaction\Exceptions\InsufficientBalanceException;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuWithdrawalCreateRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuWithdrawalCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param FlexySusuWithdrawalCreateRequest $flexySusuWithdrawalCreateRequest
     * @param FlexySusuWithdrawalCreateAction $flexySusuWithdrawalCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     * @throws MoneyMismatchException
     * @throws InsufficientBalanceException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuWithdrawalCreateRequest $flexySusuWithdrawalCreateRequest,
        FlexySusuWithdrawalCreateAction $flexySusuWithdrawalCreateAction
    ): JsonResponse {
        // Execute the FlexySusuWithdrawalCreateAction and return the JsonResponse
        return $flexySusuWithdrawalCreateAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
            request: $flexySusuWithdrawalCreateRequest->validated()
        );
    }
}
