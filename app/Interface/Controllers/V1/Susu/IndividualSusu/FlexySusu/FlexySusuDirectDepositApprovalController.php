<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuDirectDepositApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuDirectDepositApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuDirectDepositApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param PaymentInstruction $paymentInstruction
     * @param FlexySusuDirectDepositApprovalRequest $flexySusuDirectDepositApprovalRequest
     * @param FlexySusuDirectDepositApprovalAction $flexySusuDirectDepositApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        PaymentInstruction $paymentInstruction,
        FlexySusuDirectDepositApprovalRequest $flexySusuDirectDepositApprovalRequest,
        FlexySusuDirectDepositApprovalAction $flexySusuDirectDepositApprovalAction
    ): JsonResponse {
        // Execute the FlexySusuDirectDepositApprovalAction and return the JsonResponse
        return $flexySusuDirectDepositApprovalAction->execute(
            flexySusu: $flexySusu,
            paymentInstruction: $paymentInstruction,
        );
    }
}
