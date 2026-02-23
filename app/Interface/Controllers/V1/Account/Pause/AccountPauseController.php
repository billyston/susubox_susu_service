<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Account\Pause;

use App\Application\Account\Actions\Pause\AccountPauseAction;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountPauseController extends Controller
{
    /**
     * @param Request $request
     * @param RecurringDepositPause $accountPause
     * @param AccountPauseAction $accountPauseAction
     * @return JsonResponse
     */
    public function __invoke(
        Request $request,
        RecurringDepositPause $accountPause,
        AccountPauseAction $accountPauseAction
    ): JsonResponse {
        // Execute the AccountPauseAction and return the JsonResponse
        return $accountPauseAction->execute(
            request: $request->all(),
            accountPause: $accountPause,
        );
    }
}
