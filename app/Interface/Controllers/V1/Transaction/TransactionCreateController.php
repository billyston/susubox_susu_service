<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Transaction;

use App\Application\Transaction\Actions\TransactionCreateAction;
use App\Domain\Account\Models\Account;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TransactionCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Account $account,
        Request $request,
        TransactionCreateAction $transactionCreateAction
    ): JsonResponse {
        // Execute the TransactionCreateAction and return the JsonResponse
        return $transactionCreateAction->execute(
            account: $account,
            request: $request
        );
    }
}
