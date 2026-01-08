<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Models\Transaction;

final class TransactionCreateService
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @param TransactionCreateRequestDTO $requestDTO
     * @return Transaction
     */
    public function execute(
        PaymentInstruction $paymentInstruction,
        TransactionCreateRequestDTO $requestDTO
    ): Transaction {
        return Transaction::create([
            'resource_id' => $requestDTO->resourceID,
            'account_id' => $paymentInstruction->account_id,
            'payment_instruction_id' => $paymentInstruction->id,
            'transaction_category_id' => $paymentInstruction->transaction_category_id,
            'wallet_id' => $paymentInstruction->wallet->id,
            'transaction_type' => $paymentInstruction->transaction_type,
            'reference_number' => $requestDTO->reference,

            'amount' => $requestDTO->amount,
            'charge' => $paymentInstruction->charge,
            'total' => $requestDTO->amount,

            'description' => $requestDTO->description,
            'narration' => Transaction::narration(
                category: $paymentInstruction->transactionCategory->name,
                amount: $requestDTO->amount->getAmount()->toFloat(),
                account_number: $paymentInstruction->account->account_number,
                wallet: $paymentInstruction->wallet->wallet_number,
                date: $requestDTO->date,
            ),
            'date' => $requestDTO->date,
            'status_code' => $requestDTO->code,
            'status' => $requestDTO->status,
            'extra_data' => $requestDTO->toArray(),
        ]);
    }
}
