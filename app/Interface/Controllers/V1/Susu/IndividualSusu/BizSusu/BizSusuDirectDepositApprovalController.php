<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuDirectDepositApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuDirectDepositApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuDirectDepositApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param PaymentInstruction $paymentInstruction
     * @param BizSusuDirectDepositApprovalRequest $bizSusuDirectDepositApprovalRequest
     * @param BizSusuDirectDepositApprovalAction $bizSusuDirectDepositApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        PaymentInstruction $paymentInstruction,
        BizSusuDirectDepositApprovalRequest $bizSusuDirectDepositApprovalRequest,
        BizSusuDirectDepositApprovalAction $bizSusuDirectDepositApprovalAction
    ): JsonResponse {
        // Execute the BizSusuDirectDepositApprovalAction and return the JsonResponse
        return $bizSusuDirectDepositApprovalAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            paymentInstruction: $paymentInstruction,
            request: $bizSusuDirectDepositApprovalRequest->validated()
        );
    }
}
