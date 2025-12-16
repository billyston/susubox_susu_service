<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs;

use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Domain\PaymentInstruction\Services\PaymentInstructionByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Services\TransactionCreateCreditService;
use App\Domain\Transaction\Services\TransactionCreateDebitService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class TransactionCreateJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $paymentInstructionResourceId,
        public readonly TransactionCreateRequestDTO $requestDto,
    ) {
        // ...
    }

    /**
     * @throws Throwable
     * @throws SystemFailureException
     */
    public function handle(
        PaymentInstructionByResourceIdService $paymentInstructionByResource,
        TransactionCreateCreditService $transactionCreateCreditService,
        TransactionCreateDebitService $transactionCreateDebitService,
    ): void {
        // // Execute the PaymentInstructionByResourceIdService and return the resource
        $paymentInstruction = $paymentInstructionByResource->execute(
            $this->paymentInstructionResourceId
        );

        // // Determine the transaction_type and execute and return the resource
        $transaction = match ($paymentInstruction->transaction_type) {
            TransactionType::CREDIT->value => $transactionCreateCreditService->execute(
                paymentInstruction: $paymentInstruction,
                requestDto: $this->requestDto,
            ),
            TransactionType::DEBIT->value => $transactionCreateDebitService->execute(
                paymentInstruction: $paymentInstruction,
                requestDto: $this->requestDto
            ),
        };

        // Dispatch the TransactionPostProcessJob
        TransactionPostProcessJob::dispatch(
            transactionResource: $transaction->resource_id,
            isInitialDeposit: $this->requestDto->isInitialDeposit
        );

        // Dispatch the TransactionNotificationJob
        TransactionNotificationJob::dispatch(
            transactionResource: $transaction->resource_id,
            isInitialDeposit: $this->requestDto->isInitialDeposit
        );
    }
}
