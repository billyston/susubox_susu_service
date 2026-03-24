<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\PaymentInstruction\RecurringDeposit;

use App\Application\PaymentInstruction\Actions\RecurringDeposit\RecurringDepositResumeAction;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Interface\Controllers\Shared\Controller;
use Brick\Money\Exception\UnknownCurrencyException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class RecurringDepositResumeController extends Controller
{
    /**
     * @param Request $request
     * @param RecurringDepositPause $recurringDepositPause
     * @param RecurringDepositResumeAction $recurringDepositResumeAction
     * @return JsonResponse
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Request $request,
        RecurringDepositPause $recurringDepositPause,
        RecurringDepositResumeAction $recurringDepositResumeAction
    ): JsonResponse {
        // Execute the RecurringDepositResumeAction and return the JsonResponse
        return $recurringDepositResumeAction->execute(
            recurringDepositPause: $recurringDepositPause,
            request: $request->all(),
        );
    }
}
