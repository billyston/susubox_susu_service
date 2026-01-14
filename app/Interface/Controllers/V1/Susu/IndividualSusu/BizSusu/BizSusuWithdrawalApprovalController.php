<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuWithdrawalApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuWithdrawalApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        PaymentInstruction $paymentInstruction,
        BizSusuWithdrawalApprovalRequest $bizSusuWithdrawalApprovalRequest,
        BizSusuWithdrawalApprovalAction $bizSusuWithdrawalApprovalAction
    ): JsonResponse {
        // Execute the BizSusuWithdrawalApprovalAction and return the JsonResponse
        return $bizSusuWithdrawalApprovalAction->execute(
            bizSusu: $bizSusu,
            paymentInstruction: $paymentInstruction,
        );
    }
}
