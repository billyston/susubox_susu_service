<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs\AccountPause;

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
        public array $action,
    ) {
        // ..
    }

    /**
     * @param AccountPause $accountPause
     * @param array $action
     * @return self
     */
    public static function fromDomain(
        AccountPause $accountPause,
        array $action
    ): self {
        // Get the account
        $account = $accountPause->payment->account;
        $customer = $accountPause->payment->initiator;

        return new self(
            accountPause: $accountPause,
            account: $account,
            customer: $customer,
            action: $action
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
                    'action' => $this->action['action'],
                    'status' => $this->action['status'],
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
