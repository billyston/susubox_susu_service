<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\BizSusu;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class BizSusuCreateRequestDTO
{
    /**
     * @param string $accountName
     * @param string $purpose
     * @param Money $susuAmount
     * @param Money $initialDeposit
     * @param string $frequency
     * @param bool $rolloverEnabled
     * @param bool $acceptedTerms
     * @param string $walletResourceID
     */
    public function __construct(
        public string $accountName,
        public string $purpose,
        public Money $susuAmount,
        public Money $initialDeposit,
        public string $frequency,
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
        $susuAmount = Money::of(amount: $attributes['susu_amount'], currency: 'GHS');
        $initialDeposit = Money::of(amount: $attributes['initial_deposit'], currency: 'GHS');

        return new self(
            accountName: $attributes['account_name'],
            purpose: $attributes['purpose'],
            susuAmount: $susuAmount,
            initialDeposit: $initialDeposit,
            frequency: $attributes['frequency'],
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
            'purpose' => $this->purpose,
            'susu_amount' => $this->susuAmount,
            'initial_deposit' => $this->initialDeposit,
            'frequency' => $this->frequency,
            'rollover_enabled' => $this->rolloverEnabled,
            'accepted_terms' => $this->acceptedTerms,
            'wallet_id' => $this->walletResourceID,
        ];
    }
}
