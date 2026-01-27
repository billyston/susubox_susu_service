<?php

declare(strict_types=1);

namespace App\Application\Susu\ValueObjects\IndividualSusu\DailySusu\Settlement;

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionType;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;

final class DailySusuSettlementCalculationVO
{
    /**
     * @param Money $principal
     * @param Money $charges
     * @param Money $total
     * @param array $cycleResourceIDs
     * @param string $settlementScope
     */
    public function __construct(
        public Money $principal,
        public Money $charges,
        public Money $total,
        public array $cycleResourceIDs,
        public string $settlementScope
    ) {
        // ..
    }

    /**
     * @param Money $principal
     * @param Money $charges
     * @param array $cycleResourceIDs
     * @param string $settlementScope
     * @return self
     * @throws MoneyMismatchException
     */
    public static function create(
        Money $principal,
        Money $charges,
        array $cycleResourceIDs,
        string $settlementScope
    ): self {
        // Calculate the total
        $total = $principal->minus($charges);

        return new self(
            principal: $principal,
            charges: $charges,
            total: $total,
            cycleResourceIDs: $cycleResourceIDs,
            settlementScope: $settlementScope
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
                'settlement_scope' => $this->settlementScope,
                'cycle_resource_ids' => $this->cycleResourceIDs,
            ],
        ];
    }
}
