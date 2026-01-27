<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DirectDeposit;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\DirectDeposit\DailySusuDirectDepositApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DirectDeposit\DailySusuDirectDepositApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuDirectDepositApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param PaymentInstruction $paymentInstruction
     * @param DailySusuDirectDepositApprovalRequest $dailySusuDirectDepositApprovalRequest
     * @param DailySusuDirectDepositApprovalAction $dailySusuDirectDepositApprovalAction
     * @return JsonResponse
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
            dailySusu: $dailySusu,
            paymentInstruction: $paymentInstruction,
        );
    }
}
