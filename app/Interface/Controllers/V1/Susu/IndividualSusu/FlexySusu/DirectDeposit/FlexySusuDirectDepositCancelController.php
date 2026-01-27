<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\DirectDeposit;

use App\Application\Susu\Actions\IndividualSusu\FlexySusu\DirectDeposit\FlexySusuDirectDepositCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\DirectDeposit\FlexySusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuDirectDepositCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param PaymentInstruction $paymentInstruction
     * @param FlexySusuDirectDepositCancelRequest $flexySusuDirectDepositCancelRequest
     * @param FlexySusuDirectDepositCancelAction $flexySusuDirectDepositCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        PaymentInstruction $paymentInstruction,
        FlexySusuDirectDepositCancelRequest $flexySusuDirectDepositCancelRequest,
        FlexySusuDirectDepositCancelAction $flexySusuDirectDepositCancelAction
    ): JsonResponse {
        // Execute the FlexySusuDirectDepositCancelAction and return the JsonResponse
        return $flexySusuDirectDepositCancelAction->execute(
            paymentInstruction: $paymentInstruction,
        );
    }
}
