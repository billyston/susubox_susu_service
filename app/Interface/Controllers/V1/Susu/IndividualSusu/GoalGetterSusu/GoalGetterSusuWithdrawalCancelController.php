<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuWithdrawalCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuWithdrawalCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuWithdrawalCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param PaymentInstruction $paymentInstruction
     * @param GoalGetterSusuWithdrawalCancelRequest $goalGetterSusuWithdrawalCancelRequest
     * @param GoalGetterSusuWithdrawalCancelAction $goalGetterSusuWithdrawalCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        PaymentInstruction $paymentInstruction,
        GoalGetterSusuWithdrawalCancelRequest $goalGetterSusuWithdrawalCancelRequest,
        GoalGetterSusuWithdrawalCancelAction $goalGetterSusuWithdrawalCancelAction
    ): JsonResponse {
        // Execute the GoalGetterSusuWithdrawalCancelAction and return the JsonResponse
        return $goalGetterSusuWithdrawalCancelAction->execute(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu,
            paymentInstruction: $paymentInstruction,
            request: $goalGetterSusuWithdrawalCancelRequest->validated()
        );
    }
}
