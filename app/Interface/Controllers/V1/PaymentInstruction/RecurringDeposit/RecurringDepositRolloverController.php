<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\PaymentInstruction\RecurringDeposit;

use App\Application\PaymentInstruction\Actions\RecurringDeposit\RecurringDepositRolloverAction;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Interface\Controllers\Shared\Controller;
use Brick\Money\Exception\UnknownCurrencyException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class RecurringDepositRolloverController extends Controller
{
    /**
     * @param Request $request
     * @param RecurringDeposit $recurringDeposit
     * @param RecurringDepositRolloverAction $recurringDepositRolloverAction
     * @return JsonResponse
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Request $request,
        RecurringDeposit $recurringDeposit,
        RecurringDepositRolloverAction $recurringDepositRolloverAction
    ): JsonResponse {
        // Execute the RecurringDepositRolloverAction and return the JsonResponse
        return $recurringDepositRolloverAction->execute(
            recurringDeposit: $recurringDeposit,
            request: $request->all(),
        );
    }
}
