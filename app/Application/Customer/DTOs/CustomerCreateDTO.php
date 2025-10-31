<?php

declare(strict_types=1);

namespace App\Application\Customer\DTOs;

final readonly class CustomerCreateDTO
{
    public function __construct(
        public string $resource_id,
        public string $first_name,
        public string $last_name,
        public string $phone_number,
        public ?string $email = null,
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
            first_name: $attributes['first_name'],
            last_name: $attributes['last_name'],
            phone_number: $attributes['phone_number'],
            email: $attributes['email'],
        );
    }

    public function toArray(
    ): array {
        return [
            'resource_id' => $this->resource_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
        ];
    }
}
