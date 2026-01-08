<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\DailySusu;

use App\Domain\Account\Models\Account;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Transaction\Models\Transaction;
use Brick\Money\Money;

final readonly class DailySusuCycleResponseDTO
{
    /**
     * @param DailySusu $dailySusu
     * @param Account $account
     * @param PaymentInstruction $paymentInstruction
     * @param Transaction $transaction
     * @param int $expectedFrequencies
     * @param Money $expectedAmount
     * @param Money $contributionAmount
     * @param string $entryType
     * @param int $frequency
     */
    public function __construct(
        public DailySusu $dailySusu,
        public Account $account,
        public PaymentInstruction $paymentInstruction,
        public Transaction $transaction,
        public int $expectedFrequencies,
        public Money $expectedAmount,
        public Money $contributionAmount,
        public string $entryType,
        public int $frequency,
    ) {
        // ..
    }

    /**
     * @param DailySusu $dailySusu
     * @param Transaction $transaction
     * @return self
     */
    public static function fromDomain(
        DailySusu $dailySusu,
        Transaction $transaction,
    ): self {
        $definition = $dailySusu->cycleDefinition;
        $paymentInstruction = $transaction->payment;

        /**
         * Resolve entry type & frequency using match
         */
        [$entryType, $frequency] = self::resolveEntryTypeAndFrequency(
            transaction: $transaction,
            paymentInstruction: $paymentInstruction,
        );

        return new self(
            dailySusu: $dailySusu,
            account: $transaction->account,
            paymentInstruction: $paymentInstruction,
            transaction: $transaction,
            expectedFrequencies: $definition->expected_frequencies,
            expectedAmount: $definition->expected_cycle_amount,
            contributionAmount: $transaction->total,
            entryType: $entryType,
            frequency: $frequency,
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'account_cycle',
                'attributes' => [
                    'expected_frequencies' => $this->expectedFrequencies,
                    'expected_amount' => $this->expectedAmount,
                    'contribution_amount' => $this->contributionAmount,
                    'entry_type' => $this->entryType,
                    'frequency' => $this->frequency,
                ],
                'relationships' => [
                    'account' => [
                        'resource_id' => $this->account->resource_id,
                    ],
                    'transaction' => [
                        'resource_id' => $this->transaction->resource_id,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param Transaction $transaction
     * @param PaymentInstruction $paymentInstruction
     * @return array
     */
    private static function resolveEntryTypeAndFrequency(
        Transaction $transaction,
        PaymentInstruction $paymentInstruction
    ): array {
        //
        $transactionMetadata = $transaction->getMetadata();
        $paymentInstructionMetadata = $paymentInstruction->getMetadata();

        return match (true) {
            $transactionMetadata['is_initial_deposit'] === true => [
                'initial',
                (int) ($paymentInstructionMetadata['initial_deposit_frequency'] ?? 1),
            ],
            $transactionMetadata['is_initial_deposit'] === false && array_key_exists('frequencies', $paymentInstructionMetadata) => [
                'direct',
                (int) $paymentInstructionMetadata['frequencies'],
            ],
            default => [
                'recurring',
                1,
            ],
        };
    }
}
