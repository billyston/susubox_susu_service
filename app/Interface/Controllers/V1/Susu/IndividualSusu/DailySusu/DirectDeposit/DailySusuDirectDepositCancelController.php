<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DirectDeposit;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\DirectDeposit\DailySusuDirectDepositCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DirectDeposit\DailySusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuDirectDepositCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param PaymentInstruction $paymentInstruction
     * @param DailySusuDirectDepositCancelRequest $dailySusuDirectDepositCancelRequest
     * @param DailySusuDirectDepositCancelAction $dailySusuDirectDepositCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        PaymentInstruction $paymentInstruction,
        DailySusuDirectDepositCancelRequest $dailySusuDirectDepositCancelRequest,
        DailySusuDirectDepositCancelAction $dailySusuDirectDepositCancelAction
    ): JsonResponse {
        // Execute the DailySusuDirectDepositCancelAction and return the JsonResponse
        return $dailySusuDirectDepositCancelAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            paymentInstruction: $paymentInstruction,
            request: $dailySusuDirectDepositCancelRequest->validated()
        );
    }
}
