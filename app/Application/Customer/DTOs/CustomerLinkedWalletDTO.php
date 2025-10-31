<?php

declare(strict_types=1);

namespace App\Application\Customer\DTOs;

final readonly class CustomerLinkedWalletDTO
{
    public function __construct(
        public string $resource_id,
        public string $wallet_number,
        public string $wallet_name,
        public string $network_code,
    ) {
        //..
    }

    public static function fromArray(
        array $payload
    ): self {
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];

        return new self(
            resource_id: $attributes['resource_id'],
            wallet_number: $attributes['wallet_number'],
            wallet_name: $attributes['wallet_name'],
            network_code: $attributes['network_code'],
        );
    }

    public function toArray(
    ): array {
        return [
            'resource_id' => $this->resource_id,
            'wallet_number' => $this->wallet_number,
            'wallet_name' => $this->wallet_name,
            'network_code' => $this->network_code,
        ];
    }
}
