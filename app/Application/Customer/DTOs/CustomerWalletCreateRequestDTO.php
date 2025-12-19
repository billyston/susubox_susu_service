<?php

declare(strict_types=1);

namespace App\Application\Customer\DTOs;

final readonly class CustomerWalletCreateRequestDTO
{
    /**
     * @param string $resourceID
     * @param string $walletNumber
     * @param string $walletName
     * @param string $networkCode
     */
    public function __construct(
        public string $resourceID,
        public string $walletNumber,
        public string $walletName,
        public string $networkCode,
    ) {
        //..
    }

    /**
     * @param array $payload
     * @return self
     */
    public static function fromPayload(
        array $payload
    ): self {
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];

        return new self(
            resourceID: $attributes['resource_id'],
            walletNumber: $attributes['wallet_number'],
            walletName: $attributes['wallet_name'],
            networkCode: $attributes['network_code'],
        );
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        return [
            'resource_id' => $this->resourceID,
            'wallet_number' => $this->walletNumber,
            'wallet_name' => $this->walletName,
            'network_code' => $this->networkCode,
        ];
    }
}
