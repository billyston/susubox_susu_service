<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use Brick\Money\Money;
use Illuminate\Support\Str;

final readonly class TransactionCreateRequestDTO
{
    /**
     * @param array $payload
     * @param string $resourceID
     * @param string $code
     * @param string $status
     * @param string $reference
     * @param bool $isInitialDeposit
     * @param Money $amount
     * @param Money $charges
     * @param Money $total
     * @param string $mobileNumber
     * @param string $date
     * @param string $description
     * @param string $service
     * @param string $serviceCode
     * @param string $serviceCategory
     */
    public function __construct(
        public array $payload,
        public string $resourceID,
        public string $code,
        public string $status,
        public string $reference,
        public bool $isInitialDeposit,
        public Money $amount,
        public Money $charges,
        public Money $total,
        public string $mobileNumber,
        public string $date,
        public string $description,
        public string $service,
        public string $serviceCode,
        public string $serviceCategory,
    ) {
        // ..
    }

    /**
     * @param array $payload
     * @param PaymentInstruction $paymentInstruction
     * @return TransactionCreateRequestDTO
     */
    public static function fromPayload(
        array $payload,
        PaymentInstruction $paymentInstruction,
    ): self {
        $data = $payload['data'];
        $included = $data['included'];

        $transaction = $data['attributes'];
        $service = $included['service']['attributes'];

        // Extract the main resources
        $account = $paymentInstruction->account;

        // Evaluate conditions once
        $isFirstSuccessfulTransaction = $account->isFirstSuccessfulTransaction();
        $recurringDeposit = $paymentInstruction->recurringDeposit;

        $isRecurringDeposit = $service['service_code'] === TransactionCategoryCode::RECURRING_DEBIT_CODE->value;
        $hasRecurringDepositResource = $recurringDeposit !== null;
        $isInitialDeposit = $isRecurringDeposit && $isFirstSuccessfulTransaction && $hasRecurringDepositResource;

        // Trust PaymentInstruction financial values
        $amount = $paymentInstruction->amount;
        $charges = $paymentInstruction->charge;
        $total = $paymentInstruction->total;

        // Override only when rules require
        if ($isInitialDeposit) {
            $amount = $recurringDeposit->initial_amount;
            $total = $recurringDeposit->initial_amount;
        }

        return new self(
            payload: $payload,
            resourceID: Str::uuid()->toString(),
            code: $transaction['code'],
            status: $transaction['status'],
            reference: $transaction['transaction_reference'],
            isInitialDeposit: $isInitialDeposit,
            amount: $amount,
            charges: $charges,
            total: $total,
            mobileNumber: $transaction['mobile_number'],
            date: $transaction['date'],
            description: $transaction['description'],
            service: $service['service'],
            serviceCode: $service['service_code'],
            serviceCategory: $service['service_category'],
        );
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        return [
            'is_initial_deposit' => $this->isInitialDeposit,
            'payload' => $this->payload['data'],
        ];
    }
}
