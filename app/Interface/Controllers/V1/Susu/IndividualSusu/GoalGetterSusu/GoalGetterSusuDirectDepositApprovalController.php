<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuDirectDepositApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuDirectDepositApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuDirectDepositApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param PaymentInstruction $paymentInstruction
     * @param GoalGetterSusuDirectDepositApprovalRequest $goalGetterSusuDirectDepositApprovalRequest
     * @param GoalGetterSusuDirectDepositApprovalAction $goalGetterSusuDirectDepositApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        PaymentInstruction $paymentInstruction,
        GoalGetterSusuDirectDepositApprovalRequest $goalGetterSusuDirectDepositApprovalRequest,
        GoalGetterSusuDirectDepositApprovalAction $goalGetterSusuDirectDepositApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuDirectDepositApprovalAction and return the JsonResponse
        return $goalGetterSusuDirectDepositApprovalAction->execute(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu,
            paymentInstruction: $paymentInstruction,
            request: $goalGetterSusuDirectDepositApprovalRequest->validated()
        );
    }
}
