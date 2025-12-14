<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs;

use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Domain\PaymentInstruction\Services\PaymentInstructionByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Services\TransactionCreateService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
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
        TransactionCreateService $transactionCreateService,
    ): void {
        DB::transaction(function () use (
            $paymentInstructionByResource,
            $transactionCreateService,
        ): void {
            // Execute the PaymentInstructionByResourceIdService and return the resource
            $paymentInstruction = $paymentInstructionByResource->execute(
                $this->paymentInstructionResourceId
            );

            // Execute the TransactionCreateService and return the resource
            $transaction = $transactionCreateService->execute(
                $paymentInstruction,
                $this->requestDto
            );

            // Dispatch the TransactionPostProcessJob
            TransactionPostProcessJob::dispatch(
                transactionResource: $transaction->resource_id,
                isInitialDeposit: $this->requestDto->is_initial_deposit
            );

            // Dispatch the TransactionNotificationJob
            TransactionNotificationJob::dispatch(
                transactionResource: $transaction->resource_id,
                isInitialDeposit: $this->requestDto->is_initial_deposit
            );
        });
    }
}
