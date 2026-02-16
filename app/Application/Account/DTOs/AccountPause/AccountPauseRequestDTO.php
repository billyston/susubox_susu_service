<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs\AccountPause;

final readonly class AccountPauseRequestDTO
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
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];

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
            'duration' => $this->duration,
            'accepted_terms' => $this->acceptedTerms,
        ];
    }
}
