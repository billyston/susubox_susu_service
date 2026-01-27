<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Withdrawal;

use App\Application\Susu\Actions\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalApprovalRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuWithdrawalApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param PaymentInstruction $paymentInstruction
     * @param FlexySusuWithdrawalApprovalRequest $flexySusuWithdrawalApprovalRequest
     * @param FlexySusuWithdrawalApprovalAction $flexySusuWithdrawalApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        PaymentInstruction $paymentInstruction,
        FlexySusuWithdrawalApprovalRequest $flexySusuWithdrawalApprovalRequest,
        FlexySusuWithdrawalApprovalAction $flexySusuWithdrawalApprovalAction
    ): JsonResponse {
        // Execute the FlexySusuWithdrawalApprovalAction and return the JsonResponse
        return $flexySusuWithdrawalApprovalAction->execute(
            flexySusu: $flexySusu,
            paymentInstruction: $paymentInstruction,
        );
    }
}
