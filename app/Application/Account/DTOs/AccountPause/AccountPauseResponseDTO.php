<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs\AccountPause;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use Carbon\Carbon;

final readonly class AccountPauseResponseDTO
{
    /**
     * @param RecurringDepositPause $accountPause
     * @param Account $account
     * @param Customer $customer
     * @param array $action
     */
    public function __construct(
        public RecurringDepositPause $accountPause,
        public Account $account,
        public Customer $customer,
        public array $action,
    ) {
        // ..
    }

    /**
     * @param RecurringDepositPause $accountPause
     * @param array $action
     * @return self
     */
    public static function fromDomain(
        RecurringDepositPause $accountPause,
        array $action
    ): self {
        // Get the account
        $account = $accountPause->account;
        $customer = $accountPause->initiator;

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
                'type' => 'AccountPayoutLock',
                'attributes' => [
                    'resource_id' => $this->accountPause->resource_id,
                    'paused_at' => Carbon::parse($this->accountPause->paused_at)->toFormattedDateString(),
                    'expires_at' => Carbon::parse($this->accountPause->expires_at)->toFormattedDateString(),
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
