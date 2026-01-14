<?php

declare(strict_types=1);

namespace App\Application\Account\ValueObjects;

use App\Domain\Account\Models\AccountCycle;
use App\Domain\Shared\Enums\SettlementScopes;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionType;
use Brick\Money\Money;

final class AccountAutoSettlementCalculationVO
{
    /**
     * @param Money $principal
     * @param Money $charges
     * @param Money $total
     */
    public function __construct(
        public Money $principal,
        public Money $charges,
        public Money $total,
    ) {
        // ..
    }

    /**
     * @param AccountCycle $accountCycle
     * @param Money $charges
     * @return self
     */
    public static function create(
        AccountCycle $accountCycle,
        Money $charges,
    ): self {
        // Extract the main values
        $principal = $accountCycle->contributed_amount;

        return new self(
            principal: $accountCycle->contributed_amount,
            charges: $charges,
            total: $principal->minus($charges),
        );
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        return [
            'amount' => $this->principal,
            'charge' => $this->charges,
            'total' => $this->total,

            'approval_status' => Statuses::PENDING->value,
            'transaction_type' => TransactionType::DEBIT->value,

            'accepted_terms' => true,

            'extra_data' => [
                'settlement_scope' => SettlementScopes::AUTO_SETTLEMENT->value,
            ],
        ];
    }
}
