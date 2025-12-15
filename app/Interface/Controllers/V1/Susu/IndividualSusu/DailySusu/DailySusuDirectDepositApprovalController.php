<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuDirectDepositApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuDirectDepositApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuDirectDepositApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        PaymentInstruction $paymentInstruction,
        DailySusuDirectDepositApprovalRequest $dailySusuDirectDepositApprovalRequest,
        DailySusuDirectDepositApprovalAction $dailySusuDirectDepositApprovalAction
    ): JsonResponse {
        // Execute the DailySusuDirectDepositApprovalAction and return the JsonResponse
        return $dailySusuDirectDepositApprovalAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            paymentInstruction: $paymentInstruction,
            request: $dailySusuDirectDepositApprovalRequest->validated()
        );
    }
}
