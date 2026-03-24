<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs\AccountPayoutLock;

final readonly class AccountPayoutLockRequestDTO
{
    /**
     * @param string $duration
     * @param bool $acceptedTerms
     */
    public function __construct(
        public string $duration,
        public bool $acceptedTerms,
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
        // Extract the key arrays
        $data = $payload['data'];

        // Extract the main resources
        $attributes = $data['attributes'];

        return new self(
            duration: $attributes['duration'],
            acceptedTerms: filter_var($attributes['accepted_terms'], FILTER_VALIDATE_BOOLEAN),
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
