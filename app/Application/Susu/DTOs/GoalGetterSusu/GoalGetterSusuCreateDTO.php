<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\GoalGetterSusu;

use App\Application\Shared\Helpers\Helpers;
use App\Domain\Shared\Models\Duration;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class GoalGetterSusuCreateDTO
{
    public function __construct(
        public string $account_name,
        public string $purpose,
        public Money $target_amount,
        public Money $initial_deposit,
        public Money $susu_amount,
        public Duration $duration,
        public string $start_date,
        public string $frequency,
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
            account_name: $attributes['account_name'],
            purpose: $attributes['purpose'],
            target_amount: $targetAmount,
            initial_deposit: $initialDeposit,
            susu_amount: $susuAmount,
            duration: $duration,
            start_date: Helpers::calculateDate($attributes['start_date']),
            frequency: $frequency,
            accepted_terms: filter_var($attributes['accepted_terms'], FILTER_VALIDATE_BOOLEAN),
            wallet_id: $wallet['resource_id'],
        );
    }

    public function toArray(
    ): array {
        return [
            'account_name' => $this->account_name,
            'purpose' => $this->purpose,
            'target_amount' => $this->target_amount,
            'initial_deposit' => $this->initial_deposit,
            'susu_amount' => $this->susu_amount,
            'start_date' => $this->start_date,
            'duration' => $this->duration,
            'frequency' => $this->frequency,
            'accepted_terms' => $this->accepted_terms,
            'wallet_id' => $this->wallet_id,
        ];
    }
}
