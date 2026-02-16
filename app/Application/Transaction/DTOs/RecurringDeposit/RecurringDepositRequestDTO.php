<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs\RecurringDeposit;

final readonly class RecurringDepositRequestDTO
{
    public function __construct(
        public bool $status,
        public string $action,
    ) {
        //..
    }

    /**
     * @param array $payload
     * @return self
     */
    public static function fromPayload(
        array $payload,
    ): self {
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];

        return new self(
            status: $attributes['status'],
            action: $attributes['action'],
        );
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        return [
        ];
    }
}
