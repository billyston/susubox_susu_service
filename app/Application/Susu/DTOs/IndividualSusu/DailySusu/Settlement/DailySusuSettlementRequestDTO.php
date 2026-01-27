<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\IndividualSusu\DailySusu\Settlement;

use App\Domain\Shared\Enums\SettlementScopes;

final readonly class DailySusuSettlementRequestDTO
{
    /**
     * @param SettlementScopes $scope
     * @param array|null $cycleResourceIDs
     * @param bool $acceptedTerms
     */
    public function __construct(
        public SettlementScopes $scope,
        public ?array $cycleResourceIDs,
        public bool $acceptedTerms,
    ) {
        // ..
    }

    /**
     * @param array $payload
     * @return self
     */
    public static function fromPayload(
        array $payload
    ): self {
        // Extract the data, attributes and relationships
        $data = $payload['data'];
        $attributes = $data['attributes'];

        return new self(
            scope: SettlementScopes::from($attributes['settlement_scope']),
            cycleResourceIDs: $attributes['cycle_resource_ids'] ?? null,
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
