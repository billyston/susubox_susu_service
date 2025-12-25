<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Illuminate\Support\Str;

final readonly class TransactionCreateRequestDTO
{
    /**
     * @param string $resourceID
     * @param string $code
     * @param string $status
     * @param string $reference
     * @param bool $isInitialDeposit
     * @param Money $charges
     * @param Money $amount
     * @param string $mobileNumber
     * @param string $date
     * @param string $description
     * @param string $service
     * @param string $serviceCode
     * @param string $serviceCategory
     */
    public function __construct(
        public string $resourceID,
        public string $code,
        public string $status,
        public string $reference,
        public bool $isInitialDeposit,
        public Money $charges,
        public Money $amount,
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
     * @param bool $isInitialDeposit
     * @return self
     * @throws UnknownCurrencyException
     */
    public static function fromArray(
        array $payload,
        bool $isInitialDeposit,
    ): self {
        // Extract resource bodies
        $data = $payload['data'];
        $relationships = $data['relationships'];

        // Extract make resources
        $transaction = $data['attributes'];
        $service = $relationships['service']['attributes'];

        // Compute monetary values
        $amount = Money::of($transaction['amount'] ?? 0, 'GHS');
        $charges = Money::of($transaction['charges'] ?? 0, 'GHS');

        // Total should be calculated here

        return new self(
            resourceID: Str::uuid()->toString(),
            code: $transaction['code'],
            status: $transaction['status'],
            reference: $transaction['reference'],
            isInitialDeposit: $isInitialDeposit,
            charges: $charges,
            amount: $amount,
            mobileNumber: $transaction['mobile_number'],
            date: $transaction['date'],
            description: $transaction['description'],
            service: $service['service'],
            serviceCode: $service['service_code'],
            serviceCategory: $service['service_category'],
        );
    }

    /**
     * @return bool[]
     */
    public function toArray(
    ): array {
        return [
            'is_initial_deposit' => $this->isInitialDeposit,
        ];
    }
}
