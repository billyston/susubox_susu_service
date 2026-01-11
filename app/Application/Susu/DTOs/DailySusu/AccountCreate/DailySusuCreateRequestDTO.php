<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\DailySusu\AccountCreate;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class DailySusuCreateRequestDTO
{
    public function __construct(
        public string $accountName,
        public int $initialDepositFrequency,
        public Money $susuAmount,
        public Money $initialDeposit,
        public int $cycleLength,
        public int $expectedFrequencies,
        public int $commissionFrequencies,
        public int $settlementFrequencies,
        public Money $expectedCycleAmount,
        public Money $expectedSettlementAmount,
        public Money $commissionAmount,
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
        // Extract the payload data
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];
        $relationships = $data['relationships'] ?? [];
        $wallet = $relationships['wallet'] ?? [];

        // Define cycle parameters
        $cycleLength = 31;
        $commissionFrequencies = 1;
        $settlementFrequencies = $cycleLength - $commissionFrequencies;

        // Compute intermediate values
        $susuAmount = Money::of($attributes['susu_amount'], currency: 'GHS');
        $initialDeposit = $susuAmount->multipliedBy($attributes['initial_deposit']);
        $expectedCycleAmount = $susuAmount->multipliedBy(that: $cycleLength);
        $expectedSettlementAmount = $susuAmount->multipliedBy(that: $settlementFrequencies);
        $commissionAmount = $susuAmount->multipliedBy(that: 1);

        return new self(
            accountName: $attributes['account_name'],
            initialDepositFrequency: $attributes['initial_deposit'],
            susuAmount: $susuAmount,
            initialDeposit: $initialDeposit,
            cycleLength: $cycleLength,
            expectedFrequencies: $cycleLength,
            commissionFrequencies: $commissionFrequencies,
            settlementFrequencies: $cycleLength - $commissionFrequencies,
            expectedCycleAmount: $expectedCycleAmount,
            expectedSettlementAmount: $expectedSettlementAmount,
            commissionAmount: $commissionAmount,
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

            'cycle_length' => $this->cycleLength,
            'settlement_frequencies' => $this->settlementFrequencies,

            'expected_frequencies' => $this->expectedFrequencies,
            'expected_cycle_amount' => $this->expectedCycleAmount,
            'expected_settlement_amount' => $this->expectedSettlementAmount,

            'commission_frequencies' => $this->commissionFrequencies,
            'commission_amount' => $this->commissionAmount,

            'rollover_enabled' => $this->rolloverEnabled,
            'accepted_terms' => $this->acceptedTerms,
            'wallet_id' => $this->walletResourceID,
        ];
    }
}
