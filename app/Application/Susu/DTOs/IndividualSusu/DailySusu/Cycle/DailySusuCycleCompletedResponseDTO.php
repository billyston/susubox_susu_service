<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\IndividualSusu\DailySusu\Cycle;

use App\Domain\Account\Models\AccountCycle;

final readonly class DailySusuCycleCompletedResponseDTO
{
    /**
     * @param string $expectedAmount
     * @param string $contributedAmount
     * @param string $accountName
     * @param string $phoneNumber
     */
    public function __construct(
        public string $expectedAmount,
        public string $contributedAmount,
        public string $accountName,
        public string $phoneNumber,
    ) {
        // ..
    }

    /**
     * @param AccountCycle $accountCycle
     * @return self
     */
    public static function fromDomain(
        AccountCycle $accountCycle,
    ): self {
        // Extract the main resources
        $account = $accountCycle->account;
        $accountCustomer = $account->accountCustomer;
        $customer = $accountCustomer->customer;

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
                'included' => [
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
