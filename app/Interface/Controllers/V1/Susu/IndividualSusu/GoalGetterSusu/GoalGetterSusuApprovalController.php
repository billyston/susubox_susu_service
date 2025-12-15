<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuApprovalRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param GoalGetterSusuApprovalRequest $goalGetterSusuApprovalRequest
     * @param GoalGetterSusuApprovalAction $goalGetterSusuApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        GoalGetterSusuApprovalRequest $goalGetterSusuApprovalRequest,
        GoalGetterSusuApprovalAction $goalGetterSusuApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuApprovalAction and return the JsonResponse
        return $goalGetterSusuApprovalAction->execute(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu,
        );
    }
}
