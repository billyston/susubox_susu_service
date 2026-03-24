<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\PaymentInstruction;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\PaymentInstruction\DailySusuPaymentInstructionIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuPaymentInstructionIndexController extends Controller
{
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuPaymentInstructionIndexAction $dailySusuPaymentInstructionIndexAction
    ): JsonResponse {
        // Execute the DailySusuPaymentInstructionIndexAction and return the JsonResponse
        return $dailySusuPaymentInstructionIndexAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
        );
    }
}
