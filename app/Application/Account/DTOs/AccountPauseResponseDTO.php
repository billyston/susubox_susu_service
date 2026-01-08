<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use Carbon\Carbon;

final readonly class AccountPauseResponseDTO
{
    /**
     * @param AccountPause $accountPause
     * @param Account $account
     * @param Customer $customer
     */
    public function __construct(
        public AccountPause $accountPause,
        public Account $account,
        public Customer $customer,
    ) {
        // ..
    }

    /**
     * @param AccountPause $accountPause
     * @param Account $account
     * @param Customer $customer
     * @return self
     */
    public static function fromDomain(
        AccountPause $accountPause,
        Account $account,
        Customer $customer
    ): self {
        return new self(
            accountPause: $accountPause,
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
                    'resource_id' => $this->accountPause->resource_id,
                    'paused_at' => Carbon::parse($this->accountPause->paused_at)->toFormattedDateString(),
                    'resumed_at' => Carbon::parse($this->accountPause->resumed_at)->toFormattedDateString(),
                    'status' => $this->accountPause->status,
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
