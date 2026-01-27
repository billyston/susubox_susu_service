<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\DirectDeposit;

use App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\DirectDeposit\GoalGetterSusuDirectDepositCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\DirectDeposit\GoalGetterSusuDirectDepositCancelRequest;
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
            paymentInstruction: $paymentInstruction,
        );
    }
}
