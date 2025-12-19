<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\DailySusu;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class DailySusuCreateRequestDTO
{
    public function __construct(
        public string $accountName,
        public Money $susuAmount,
        public Money $initialDeposit,
        public bool $rolloverEnabled,
        public bool $acceptedTerms,
        public string $walletResourceID,
    ) {
        //..
    }

    /**
     * @param array $payload
     * @return self
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
        $susuAmount = Money::of($attributes['susu_amount'], 'GHS');

        return new self(
            accountName: $attributes['account_name'],
            susuAmount: $susuAmount,
            initialDeposit: $susuAmount->multipliedBy($attributes['initial_deposit']),
            rolloverEnabled: filter_var($attributes['rollover_enabled'], FILTER_VALIDATE_BOOLEAN),
            acceptedTerms: filter_var($attributes['accepted_terms'], FILTER_VALIDATE_BOOLEAN),
            walletResourceID: $wallet['resource_id'],
        );
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        return [
            'account_name' => $this->accountName,
            'susu_amount' => $this->susuAmount,
            'initial_deposit' => $this->initialDeposit,
            'rollover_enabled' => $this->rolloverEnabled,
            'accepted_terms' => $this->acceptedTerms,
            'wallet_id' => $this->walletResourceID,
        ];
    }
}
