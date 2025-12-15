<?php

declare(strict_types=1);

namespace App\Application\Transaction\ValueObject;

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\DepositType;
use App\Domain\Transaction\Enums\TransactionType;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class DirectDepositValueObject
{
    public function __construct(
        public string $deposit_type,
        public ?int $frequencies,
        public ?Money $amount,
        public ?Money $charge,
        public Money $total,
    ) {
        // ..
    }

    /**
     * @throws UnknownCurrencyException
     * @throws RoundingNecessaryException
     * @throws NumberFormatException
     * @throws MoneyMismatchException
     */
    public static function create(
        array $payload,
        Money $susuAmount = null,
        Money $charge = null,
    ): self {
        $data = $payload['data']['attributes'];

        // Determine amount based on deposit type
        $depositType = $data['deposit_type'];
        $frequencies = $data['frequencies'] ?? 0;

        if ($depositType === DepositType::FREQUENCY->value) {
            $amount = $susuAmount->multipliedBy($frequencies);
        } else {
            $amount = Money::of($data['amount'] ?? 0, 'GHS');
            $frequencies = null;
        }

        // Get the charge
        $charge = $charge ?? Money::of(0.00, 'GHS');

        return new self(
            deposit_type: $depositType,
            frequencies: $frequencies,
            amount: $amount,
            charge: $charge,
            total: $amount->plus($charge),
        );
    }

    public function toArray(
    ): array {
        return [
            'amount' => $this->amount,
            'charge' => $this->charge,
            'total' => $this->total,
            'approval_status' => Statuses::PENDING->value,
            'transaction_type' => TransactionType::CREDIT->value,
            'accepted_terms' => true,

            'extra_data' => [
                'deposit_type' => $this->deposit_type,
                'frequencies' => $this->frequencies,
            ],
        ];
    }
}
