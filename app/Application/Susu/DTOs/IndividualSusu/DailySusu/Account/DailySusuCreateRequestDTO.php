<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\IndividualSusu\DailySusu\Account;

use App\Domain\Shared\Enums\Currency;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class DailySusuCreateRequestDTO
{
    public function __construct(
        public string $accountName,
        public int $initialDepositFrequency,
        public Money $susuAmount,
        public Money $initialDeposit,
        public Money $charge,
        public int $cycleLength,
        public int $expectedFrequencies,
        public int $commissionFrequencies,
        public int $payoutFrequencies,
        public Money $expectedCycleAmount,
        public Money $expectedPayoutAmount,
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
        $data = $payload['data'];
        $included = $data['included'];

        // Extract the main resources
        $attributes = $data['attributes'];
        $wallet = $included['wallet'];

        // Define cycle parameters
        $cycleLength = 31;
        $commissionFrequencies = 1;
        $settlementFrequencies = $cycleLength - $commissionFrequencies;

        // Compute intermediate values
        $susuAmount = Money::of($attributes['susu_amount'], currency: Currency::GHANA_CEDI->value);
        $initialDeposit = $susuAmount->multipliedBy($attributes['initial_deposit']);
        $charge = Money::of(0.00, currency: Currency::GHANA_CEDI->value);
        $expectedCycleAmount = $susuAmount->multipliedBy(that: $cycleLength);
        $expectedSettlementAmount = $susuAmount->multipliedBy(that: $settlementFrequencies);
        $commissionAmount = $susuAmount->multipliedBy(that: 1);

        return new self(
            accountName: $attributes['account_name'],
            initialDepositFrequency: $attributes['initial_deposit'],
            susuAmount: $susuAmount,
            initialDeposit: $initialDeposit,
            charge: $charge,
            cycleLength: $cycleLength,
            expectedFrequencies: $cycleLength,
            commissionFrequencies: $commissionFrequencies,
            payoutFrequencies: $cycleLength - $commissionFrequencies,
            expectedCycleAmount: $expectedCycleAmount,
            expectedPayoutAmount: $expectedSettlementAmount,
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
        ];
    }
}
