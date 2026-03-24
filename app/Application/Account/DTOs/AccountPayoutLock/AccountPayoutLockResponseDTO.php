<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs\AccountPayoutLock;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Customer\Models\Customer;
use Carbon\Carbon;

final readonly class AccountPayoutLockResponseDTO
{
    /**
     * @param AccountPayoutLock $accountLock
     * @param Account $account
     * @param Customer $customer
     */
    public function __construct(
        public AccountPayoutLock $accountLock,
        public Account $account,
        public Customer $customer,
    ) {
        // ..
    }

    /**
     * @param AccountPayoutLock $accountPayoutLock
     * @param Account $account
     * @param Customer $customer
     * @return self
     */
    public static function fromDomain(
        AccountPayoutLock $accountPayoutLock,
        Account $account,
        Customer $customer
    ): self {
        return new self(
            accountLock: $accountPayoutLock,
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
                'type' => 'AccountPayoutLock',
                'attributes' => [
                    'resource_id' => $this->accountLock->resource_id,
                    'locked_at' => Carbon::parse($this->accountLock->locked_at)->toFormattedDateString(),
                    'expires_at' => Carbon::parse($this->accountLock->expires_at)->toFormattedDateString(),
                    'status' => $this->accountLock->status,
                ],
                'included' => [
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
