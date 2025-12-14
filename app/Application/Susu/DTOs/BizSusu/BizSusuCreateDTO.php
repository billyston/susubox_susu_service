<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\BizSusu;

use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class BizSusuCreateDTO
{
    public function __construct(
        public string $account_name,
        public string $purpose,
        public Money $susu_amount,
        public Money $initial_deposit,
        public string $frequency,
        public bool $rollover_enabled,
        public bool $accepted_terms,
        public string $wallet_id,
    ) {
        //..
    }

    /**
     * @throws RoundingNecessaryException
     * @throws UnknownCurrencyException
     * @throws NumberFormatException
     */
    public static function fromArray(
        array $payload
    ): self {
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];
        $relationships = $data['relationships'] ?? [];
        $wallet = $relationships['wallet'] ?? [];

        // Compute intermediate values
        $susu_amount = Money::of(amount: $attributes['susu_amount'], currency: 'GHS');
        $initial_deposit = Money::of(amount: $attributes['initial_deposit'], currency: 'GHS');

        return new self(
            account_name: $attributes['account_name'],
            purpose: $attributes['purpose'],
            susu_amount: $susu_amount,
            initial_deposit: $initial_deposit,
            frequency: $attributes['frequency'],
            rollover_enabled: filter_var($attributes['rollover_enabled'], FILTER_VALIDATE_BOOLEAN),
            accepted_terms: filter_var($attributes['accepted_terms'], FILTER_VALIDATE_BOOLEAN),
            wallet_id: $wallet['resource_id'],
        );
    }

    public function toArray(
    ): array {
        return [
            'account_name' => $this->account_name,
            'purpose' => $this->purpose,
            'susu_amount' => $this->susu_amount,
            'initial_deposit' => $this->initial_deposit,
            'frequency' => $this->frequency,
            'rollover_enabled' => $this->rollover_enabled,
            'accepted_terms' => $this->accepted_terms,
            'wallet_id' => $this->wallet_id,
        ];
    }
}
