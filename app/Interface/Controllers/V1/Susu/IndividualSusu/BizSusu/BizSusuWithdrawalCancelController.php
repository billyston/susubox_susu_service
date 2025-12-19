<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuWithdrawalCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuWithdrawalCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param PaymentInstruction $paymentInstruction
     * @param BizSusuWithdrawalCancelRequest $bizSusuWithdrawalCancelRequest
     * @param BizSusuWithdrawalCancelAction $bizSusuWithdrawalCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        PaymentInstruction $paymentInstruction,
        BizSusuWithdrawalCancelRequest $bizSusuWithdrawalCancelRequest,
        BizSusuWithdrawalCancelAction $bizSusuWithdrawalCancelAction
    ): JsonResponse {
        // Execute the BizSusuWithdrawalCancelAction and return the JsonResponse
        return $bizSusuWithdrawalCancelAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            paymentInstruction: $paymentInstruction,
            request: $bizSusuWithdrawalCancelRequest->validated()
        );
    }
}
