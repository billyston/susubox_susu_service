<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\PaymentInstruction\RecurringDeposit;

use App\Application\PaymentInstruction\Actions\RecurringDeposit\RecurringDepositPauseAction;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Interface\Controllers\Shared\Controller;
use Brick\Money\Exception\UnknownCurrencyException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class RecurringDepositPauseController extends Controller
{
    /**
     * @param Request $request
     * @param RecurringDepositPause $recurringDepositPause
     * @param RecurringDepositPauseAction $recurringDepositPauseAction
     * @return JsonResponse
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Request $request,
        RecurringDepositPause $recurringDepositPause,
        RecurringDepositPauseAction $recurringDepositPauseAction
    ): JsonResponse {
        // Execute the RecurringDepositPauseAction and return the JsonResponse
        return $recurringDepositPauseAction->execute(
            recurringDepositPause: $recurringDepositPause,
            request: $request->all(),
        );
    }
}
