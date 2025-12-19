<?php

declare(strict_types=1);

namespace App\Application\Customer\DTOs;

final readonly class CustomerCreateRequestDTO
{
    /**
     * @param string $resourceID
     * @param string $firstName
     * @param string $lastName
     * @param string $phoneNumber
     * @param string|null $email
     */
    public function __construct(
        public string $resourceID,
        public string $firstName,
        public string $lastName,
        public string $phoneNumber,
        public ?string $email = null,
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
            firstName: $attributes['first_name'],
            lastName: $attributes['last_name'],
            phoneNumber: $attributes['phone_number'],
            email: $attributes['email'],
        );
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        return [
            'resource_id' => $this->resourceID,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone_number' => $this->phoneNumber,
            'email' => $this->email,
        ];
    }
}
