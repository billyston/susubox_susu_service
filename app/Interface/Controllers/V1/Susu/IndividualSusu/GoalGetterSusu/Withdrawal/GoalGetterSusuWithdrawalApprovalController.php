<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Withdrawal;

use App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Withdrawal\GoalGetterSusuWithdrawalApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\Withdrawal\GoalGetterSusuWithdrawalApprovalRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuWithdrawalApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param PaymentInstruction $paymentInstruction
     * @param GoalGetterSusuWithdrawalApprovalRequest $goalGetterSusuWithdrawalApprovalRequest
     * @param GoalGetterSusuWithdrawalApprovalAction $goalGetterSusuWithdrawalApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        PaymentInstruction $paymentInstruction,
        GoalGetterSusuWithdrawalApprovalRequest $goalGetterSusuWithdrawalApprovalRequest,
        GoalGetterSusuWithdrawalApprovalAction $goalGetterSusuWithdrawalApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuWithdrawalApprovalAction and return the JsonResponse
        return $goalGetterSusuWithdrawalApprovalAction->execute(
            goalGetterSusu: $goalGetterSusu,
            paymentInstruction: $paymentInstruction,
        );
    }
}
