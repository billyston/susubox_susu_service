<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuDirectDepositCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuDirectDepositCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        PaymentInstruction $paymentInstruction,
        BizSusuDirectDepositCancelRequest $bizSusuDirectDepositCancelRequest,
        BizSusuDirectDepositCancelAction $bizSusuDirectDepositCancelAction
    ): JsonResponse {
        // Execute the BizSusuDirectDepositCancelAction and return the JsonResponse
        return $bizSusuDirectDepositCancelAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            paymentInstruction: $paymentInstruction,
            request: $bizSusuDirectDepositCancelRequest->validated()
        );
    }
}
