<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\IndividualSusu\DailySusu\Cycle;

use App\Domain\Account\Models\AccountCycle;

final readonly class DailySusuCycleCompletedResponseDTO
{
    public function __construct(
        public string $expectedAmount,
        public string $contributedAmount,
        public string $accountName,
        public string $phoneNumber,
    ) {
        // ..
    }

    public static function fromDomain(
        AccountCycle $accountCycle,
    ): self {
        // Extract the Account
        $account = $accountCycle->account;

        // Extract the Customer
        $customer = $account->accountable->customer;

        return new self(
            expectedAmount: $accountCycle->expected_amount->getAmount()->__toString(),
            contributedAmount: $accountCycle->contributed_amount->getAmount()->__toString(),
            accountName: $account->account_name,
            phoneNumber: $customer->phone_number,
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'AccountCycle',
                'attributes' => [
                    'expected_amount' => $this->expectedAmount,
                    'contributed_amount' => $this->contributedAmount,
                ],
                'relationships' => [
                    'account' => [
                        'attributes' => [
                            'account_name' => $this->accountName,
                        ],
                    ],
                    'customer' => [
                        'attributes' => [
                            'phone_number' => $this->phoneNumber,
                        ],
                    ],
                ],
            ],
        ];
    }
}
