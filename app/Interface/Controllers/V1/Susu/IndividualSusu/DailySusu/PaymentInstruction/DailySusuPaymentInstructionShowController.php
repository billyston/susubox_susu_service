<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\PaymentInstruction;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\PaymentInstruction\DailySusuPaymentInstructionShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuPaymentInstructionShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param PaymentInstruction $paymentInstruction
     * @param DailySusuPaymentInstructionShowAction $dailySusuPaymentInstructionShowAction
     * @return JsonResponse
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        PaymentInstruction $paymentInstruction,
        DailySusuPaymentInstructionShowAction $dailySusuPaymentInstructionShowAction
    ): JsonResponse {
        // Execute the DailySusuPaymentInstructionShowAction and return the JsonResponse
        return $dailySusuPaymentInstructionShowAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            paymentInstruction: $paymentInstruction,
        );
    }
}
