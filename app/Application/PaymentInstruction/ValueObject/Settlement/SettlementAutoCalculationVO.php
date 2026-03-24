<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\ValueObject\Settlement;

use App\Domain\Account\Models\AccountCycle;
use App\Domain\Shared\Enums\SettlementScopes;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionType;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;

final class SettlementAutoCalculationVO
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
     * @return self
     * @throws MoneyMismatchException
     */
    public static function create(
        AccountCycle $accountCycle,
    ): self {
        // Extract the main values
        $principal = $accountCycle->contributed_amount;
        $charges = $accountCycle->accountCycleDefinition->commission_amount;

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

            'metadata' => [
                'settlement_scope' => SettlementScopes::AUTO_SETTLEMENT->value,
            ],
        ];
    }
}
