<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\FlexySusu;

use Brick\Money\Money;

final readonly class FlexySusuCreateDTO
{
    public function __construct(
        public string $account_name,
        public string $purpose,
        public Money $susu_amount,
        public Money $initial_deposit,
        public bool $accepted_terms,
        public string $linked_wallet_id,
        public string $wallet_number,
    ) {
        //..
    }

    public static function fromArray(
        array $payload
    ): self {
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];
        $relationships = $data['relationships'] ?? [];
        $linkedWallet = $relationships['linked_wallet'] ?? [];

        // Compute intermediate values
        $susu_amount = Money::of(0.00, 'GHS');
        $initial_deposit = Money::of($attributes['initial_deposit'], 'GHS');

        return new self(
            account_name: $attributes['account_name'],
            purpose: $attributes['purpose'],
            susu_amount: $susu_amount,
            initial_deposit: $initial_deposit,
            accepted_terms: filter_var($attributes['accepted_terms'], FILTER_VALIDATE_BOOLEAN),
            linked_wallet_id: $linkedWallet['resource_id'],
            wallet_number: $linkedWallet['attributes']['wallet_number'],
        );
    }

    public function toArray(
    ): array {
        return [
            'account_name' => $this->account_name,
            'purpose' => $this->purpose,
            'susu_amount' => $this->susu_amount,
            'initial_deposit' => $this->initial_deposit,
            'accepted_terms' => $this->accepted_terms,
            'linked_wallet_id' => $this->linked_wallet_id,
            'wallet_number' => $this->wallet_number,
        ];
    }
}
