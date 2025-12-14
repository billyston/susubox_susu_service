<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Transaction;

use App\Application\Transaction\Actions\TransactionCreateAction;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Interface\Controllers\Shared\Controller;
use Brick\Money\Exception\UnknownCurrencyException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TransactionCreateController extends Controller
{
    /**
     * @param Request $request
     * @param PaymentInstruction $paymentInstruction
     * @param TransactionCreateAction $transactionCreateAction
     * @return JsonResponse
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Request $request,
        PaymentInstruction $paymentInstruction,
        TransactionCreateAction $transactionCreateAction
    ): JsonResponse {
        // Execute the TransactionCreateAction and return the JsonResponse
        return $transactionCreateAction->execute(
            paymentInstruction: $paymentInstruction,
            request: $request->all(),
        );
    }
}
