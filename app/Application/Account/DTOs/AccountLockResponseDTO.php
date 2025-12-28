<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use Carbon\Carbon;

final readonly class AccountLockResponseDTO
{
    /**
     * @param AccountLock $accountLock
     * @param Customer $customer
     */
    public function __construct(
        public AccountLock $accountLock,
        public Account $account,
        public Customer $customer,
    ) {
        // ..
    }

    /**
     * @param AccountLock $accountLock
     * @param Customer $customer
     * @return self
     */
    public static function fromDomain(
        AccountLock $accountLock,
        Account $account,
        Customer $customer
    ): self {
        return new self(
            accountLock: $accountLock,
            account: $account,
            customer: $customer,
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'AccountLock',
                'attributes' => [
                    'resource_id' => $this->accountLock->resource_id,
                    'locked_at' => Carbon::parse($this->accountLock->locked_at)->isoFormat(format: 'MM/DD/YYYY'),
                    'unlocked_at' => Carbon::parse($this->accountLock->unlocked_at)->isoFormat(format: 'MM/DD/YYYY'),
                ],
                'relationships' => [
                    'account' => [
                        'type' => 'Account',
                        'attributes' => [
                            'account_name' => $this->account->account_name,
                            'account_number' => $this->account->account_number,
                        ],
                    ],
                    'customer' => [
                        'type' => 'Customer',
                        'attributes' => [
                            'resource_id' => $this->customer['resource_id'],
                            'phone_number' => $this->customer['phone_number'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
