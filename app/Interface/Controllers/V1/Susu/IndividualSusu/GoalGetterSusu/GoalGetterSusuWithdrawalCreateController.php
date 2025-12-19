<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuWithdrawalCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Transaction\Exceptions\InsufficientBalanceException;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuWithdrawalCreateRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuWithdrawalCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param GoalGetterSusuWithdrawalCreateRequest $goalGetterSusuWithdrawalCreateRequest
     * @param GoalGetterSusuWithdrawalCreateAction $goalGetterSusuWithdrawalCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     * @throws MoneyMismatchException
     * @throws InsufficientBalanceException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        GoalGetterSusuWithdrawalCreateRequest $goalGetterSusuWithdrawalCreateRequest,
        GoalGetterSusuWithdrawalCreateAction $goalGetterSusuWithdrawalCreateAction
    ): JsonResponse {
        // Execute the GoalGetterSusuWithdrawalCreateAction and return the JsonResponse
        return $goalGetterSusuWithdrawalCreateAction->execute(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu,
            request: $goalGetterSusuWithdrawalCreateRequest->validated()
        );
    }
}
