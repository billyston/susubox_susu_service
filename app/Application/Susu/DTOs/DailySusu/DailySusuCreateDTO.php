<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\DailySusu;

use Brick\Math\Exception\MathException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class DailySusuCreateDTO
{
    public function __construct(
        public string $account_name,
        public Money $susu_amount,
        public Money $initial_deposit,
        public bool $rollover_enabled,
        public bool $accepted_terms,
        public string $wallet_id,
    ) {
        //..
    }

    /**
     * @throws UnknownCurrencyException
     */
    public static function fromArray(
        array $payload
    ): self {
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];
        $relationships = $data['relationships'] ?? [];
        $wallet = $relationships['wallet'] ?? [];

        // Compute intermediate values
        $susu_amount = Money::of($attributes['susu_amount'], 'GHS');

        return new self(
            account_name: $attributes['account_name'],
            susu_amount: $susu_amount,
            initial_deposit: $susu_amount->multipliedBy($attributes['initial_deposit']),
            rollover_enabled: filter_var($attributes['rollover_enabled'], FILTER_VALIDATE_BOOLEAN),
            accepted_terms: filter_var($attributes['accepted_terms'], FILTER_VALIDATE_BOOLEAN),
            wallet_id: $wallet['resource_id'],
        );
    }

    /**
     * @throws MathException
     */
    public function toArray(
    ): array {
        return [
            'account_name' => $this->account_name,
            'susu_amount' => $this->susu_amount,
            'initial_deposit' => $this->initial_deposit,
            'rollover_enabled' => $this->rollover_enabled,
            'accepted_terms' => $this->accepted_terms,
            'wallet_id' => $this->wallet_id,
        ];
    }
}
