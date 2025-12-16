<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs;

use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Illuminate\Support\Str;

final readonly class TransactionCreateRequestDTO
{
    public function __construct(
        public string $resource_id,
        public string $code,
        public string $status,
        public string $reference,
        public bool $isInitialDeposit,
        public Money $amount,
        public Money $charges,
        public Money $total,
        public string $mobile_number,
        public string $date,
        public string $description,
        public string $service,
        public string $service_code,
        public string $service_category,
    ) {
        // ..
    }

    /**
     * @throws UnknownCurrencyException
     * @throws RoundingNecessaryException
     * @throws NumberFormatException
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
        $total = Money::of($transaction['total'] ?? 0, 'GHS');

        return new self(
            resource_id: Str::uuid()->toString(),
            code: $transaction['code'],
            status: $transaction['status'],
            reference: $transaction['reference'],
            isInitialDeposit: $isInitialDeposit,
            amount: $amount,
            charges: $charges,
            total: $total,
            mobile_number: $transaction['mobile_number'],
            date: $transaction['date'],
            description: $transaction['description'],
            service: $service['service'],
            service_code: $service['service_code'],
            service_category: $service['service_category'],
        );
    }

    public function toArray(
    ): array {
        return [
            'is_initial_deposit' => $this->isInitialDeposit,
        ];
    }
}
