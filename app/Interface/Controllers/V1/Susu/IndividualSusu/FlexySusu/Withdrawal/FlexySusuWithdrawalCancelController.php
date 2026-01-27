<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Withdrawal;

use App\Application\Susu\Actions\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuWithdrawalCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param PaymentInstruction $paymentInstruction
     * @param FlexySusuWithdrawalCancelRequest $flexySusuWithdrawalCancelRequest
     * @param FlexySusuWithdrawalCancelAction $flexySusuWithdrawalCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        PaymentInstruction $paymentInstruction,
        FlexySusuWithdrawalCancelRequest $flexySusuWithdrawalCancelRequest,
        FlexySusuWithdrawalCancelAction $flexySusuWithdrawalCancelAction
    ): JsonResponse {
        // Execute the FlexySusuWithdrawalCancelAction and return the JsonResponse
        return $flexySusuWithdrawalCancelAction->execute(
            paymentInstruction: $paymentInstruction,
            request: $flexySusuWithdrawalCancelRequest->validated()
        );
    }
}
