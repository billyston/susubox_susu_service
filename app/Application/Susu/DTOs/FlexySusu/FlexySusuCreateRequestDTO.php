<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\FlexySusu;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class FlexySusuCreateRequestDTO
{
    public function __construct(
        public string $accountName,
        public string $purpose,
        public Money $susuAmount,
        public Money $initialDeposit,
        public bool $acceptedTerms,
        public string $walletResourceID,
    ) {
        //..
    }

    /**
     * @throws UnknownCurrencyException
     */
    public static function fromPayload(
        array $payload
    ): self {
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];
        $relationships = $data['relationships'] ?? [];
        $wallet = $relationships['wallet'] ?? [];

        // Compute intermediate values
        $susuAmount = Money::of(0.00, 'GHS');
        $initialDeposit = Money::of($attributes['initial_deposit'], 'GHS');

        return new self(
            accountName: $attributes['account_name'],
            purpose: $attributes['purpose'],
            susuAmount: $susuAmount,
            initialDeposit: $initialDeposit,
            acceptedTerms: filter_var($attributes['accepted_terms'], FILTER_VALIDATE_BOOLEAN),
            walletResourceID: $wallet['resource_id'],
        );
    }

    public function toArray(
    ): array {
        return [
            'account_name' => $this->accountName,
            'purpose' => $this->purpose,
            'susu_amount' => $this->susuAmount,
            'initial_deposit' => $this->initialDeposit,
            'accepted_terms' => $this->acceptedTerms,
            'wallet_id' => $this->walletResourceID,
        ];
    }
}
