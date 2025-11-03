<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs;

use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class DirectDepositCreateDTO
{
    public function __construct(
        public string $deposit_type,
        public ?int $frequencies,
        public ?Money $amount,
        public bool $accepted_terms,
    ) {
        // ..
    }

    /**
     * @throws UnknownCurrencyException
     * @throws RoundingNecessaryException
     * @throws NumberFormatException
     */
    public static function fromArray(
        array $payload
    ): self {
        $attributes = $payload['data']['attributes'];

        // Compute intermediate values
        $amount = isset($attributes['amount']) ? Money::of(amount: $attributes['amount'], currency: 'GHS') : null;

        return new self(
            deposit_type: $attributes['deposit_type'],
            frequencies: $attributes['frequencies'] ?? null,
            amount: $amount,
            accepted_terms: (bool) $attributes['accepted_terms'],
        );
    }
}
