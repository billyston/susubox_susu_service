<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;

final class PaymentInstructionInternalReferenceUpdateService
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @param string $reference
     * @return void
     */
    public static function execute(
        PaymentInstruction $paymentInstruction,
        string $reference,
    ): void {
        // Return if internal_reference is available
        if (! blank($paymentInstruction->internal_reference)) {
            return;
        }

        // Update the PaymentInstruction internal_reference
        $paymentInstruction->update([
            'internal_reference' => $reference,
        ]);
    }
}
