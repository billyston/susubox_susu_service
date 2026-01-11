<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs;

use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Domain\PaymentInstruction\Services\PaymentInstructionByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Services\TransactionCreditCreateService;
use App\Domain\Transaction\Services\TransactionDebitCreateService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class TransactionCreateJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $paymentInstructionResourceId
     * @param TransactionCreateRequestDTO $requestDTO
     */
    public function __construct(
        public readonly string $paymentInstructionResourceId,
        public readonly TransactionCreateRequestDTO $requestDTO,
    ) {
        // ...
    }

    /**
     * @param PaymentInstructionByResourceIdService $paymentInstructionByResource
     * @param TransactionCreditCreateService $transactionCreditCreateService
     * @param TransactionDebitCreateService $transactionDebitCreateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        PaymentInstructionByResourceIdService $paymentInstructionByResource,
        TransactionCreditCreateService $transactionCreditCreateService,
        TransactionDebitCreateService $transactionDebitCreateService,
    ): void {
        // Execute the PaymentInstructionByResourceIdService and return the resource
        $paymentInstruction = $paymentInstructionByResource->execute(
            $this->paymentInstructionResourceId
        );

        // Determine the transaction_type and execute and return the resource
        match ($paymentInstruction->transaction_type) {
            // Execute the TransactionCreditCreateService and return The transaction resource
            TransactionType::CREDIT->value => $transactionCreditCreateService->execute(
                paymentInstruction: $paymentInstruction,
                requestDTO: $this->requestDTO,
            ),

            // Execute the TransactionDebitCreateService and return The transaction resource
            TransactionType::DEBIT->value => $transactionDebitCreateService->execute(
                paymentInstruction: $paymentInstruction,
                requestDTO: $this->requestDTO
            ),
        };
    }
}
