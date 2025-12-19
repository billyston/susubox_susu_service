<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\GoalGetterSusu;

use App\Application\Shared\Helpers\Helpers;
use App\Domain\Shared\Models\Duration;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class GoalGetterSusuCreateRequestDTO
{
    public function __construct(
        public string $accountName,
        public string $purpose,
        public Money $targetAmount,
        public Money $initialDeposit,
        public Money $susuAmount,
        public Duration $duration,
        public string $startDate,
        public string $frequency,
        public bool $acceptedTerms,
        public string $wallet_id,
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
        $targetAmount = Money::of($attributes['target_amount'], 'GHS');
        $initialDeposit = Money::of($attributes['initial_deposit'], 'GHS');
        $duration = Helpers::getDaysInDuration($attributes['duration']);
        $frequency = $attributes['frequency'];
        $susuAmount = Money::of(
            Helpers::calculateDebit(
                amount: $targetAmount->getAmount()->toFloat(),
                frequency: $frequency,
                duration: $duration->code
            ),
            currency: 'GHS'
        );

        return new self(
            accountName: $attributes['account_name'],
            purpose: $attributes['purpose'],
            targetAmount: $targetAmount,
            initialDeposit: $initialDeposit,
            susuAmount: $susuAmount,
            duration: $duration,
            startDate: Helpers::calculateDate($attributes['start_date']),
            frequency: $frequency,
            acceptedTerms: filter_var($attributes['accepted_terms'], FILTER_VALIDATE_BOOLEAN),
            wallet_id: $wallet['resource_id'],
        );
    }

    public function toArray(
    ): array {
        return [
            'account_name' => $this->accountName,
            'purpose' => $this->purpose,
            'target_amount' => $this->targetAmount,
            'initial_deposit' => $this->initialDeposit,
            'susu_amount' => $this->susuAmount,
            'start_date' => $this->startDate,
            'duration' => $this->duration,
            'frequency' => $this->frequency,
            'accepted_terms' => $this->acceptedTerms,
            'wallet_id' => $this->wallet_id,
        ];
    }
}
