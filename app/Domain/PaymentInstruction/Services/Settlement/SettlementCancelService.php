<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\Settlement;

use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionCancelService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SettlementCancelService
{
    private PaymentInstructionCancelService $paymentInstructionCancelService;

    /**
     * @param PaymentInstructionCancelService $paymentInstructionCancelService
     */
    public function __construct(
        PaymentInstructionCancelService $paymentInstructionCancelService
    ) {
        $this->paymentInstructionCancelService = $paymentInstructionCancelService;
    }

    /**
     * @param Settlement $settlement
     * @return bool
     * @throws SystemFailureException
     */
    public function execute(
        Settlement $settlement,
    ): bool {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $settlement,
                ) {
                    // Extract the key resources
                    $paymentInstruction = $settlement->paymentInstruction;

                    // Execute the PaymentInstructionCancelService
                    $cancelPaymentInstruction = $this->paymentInstructionCancelService->execute(
                        paymentInstruction: $paymentInstruction
                    );

                    // Guard and cancel the $settlement
                    if ($cancelPaymentInstruction) {
                        return $settlement->update([
                            'status' => Statuses::CANCELLED->value,
                        ]);
                    }

                    // Return false
                    return false;
                }
            );
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in SettlementCancelService', [
                'settlement' => $settlement,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'code' => $throwable->getCode(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'There was an error while trying to cancel the settlement.',
            );
        }
    }
}
