<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuDirectDepositCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuDirectDepositCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param PaymentInstruction $paymentInstruction
     * @param GoalGetterSusuDirectDepositCancelRequest $goalGetterSusuDirectDepositCancelRequest
     * @param GoalGetterSusuDirectDepositCancelAction $goalGetterSusuDirectDepositCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        PaymentInstruction $paymentInstruction,
        GoalGetterSusuDirectDepositCancelRequest $goalGetterSusuDirectDepositCancelRequest,
        GoalGetterSusuDirectDepositCancelAction $goalGetterSusuDirectDepositCancelAction
    ): JsonResponse {
        // Execute the GoalGetterSusuDirectDepositCancelAction and return the JsonResponse
        return $goalGetterSusuDirectDepositCancelAction->execute(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu,
            paymentInstruction: $paymentInstruction,
            request: $goalGetterSusuDirectDepositCancelRequest->validated()
        );
    }
}
