<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\DTOs\RecurringDeposit;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Models\SusuScheme;
use Carbon\Carbon;

final readonly class RecurringDepositPausedResponseDTO
{
    /**
     * @param RecurringDepositPause $recurringDepositPause
     * @param RecurringDeposit $recurringDeposit
     * @param Account $account
     * @param Customer $customer
     * @param SusuScheme $susuScheme
     */
    public function __construct(
        public RecurringDepositPause $recurringDepositPause,
        public RecurringDeposit $recurringDeposit,
        public Account $account,
        public Customer $customer,
        public SusuScheme $susuScheme,
    ) {
        // ..
    }

    /**
     * @param RecurringDepositPause $recurringDepositPause
     * @return self
     */
    public static function fromDomain(
        RecurringDepositPause $recurringDepositPause,
    ): self {
        // Extract the key resources
        $recurringDeposit = $recurringDepositPause->recurringDeposit;
        $account = $recurringDeposit->account;
        $customer = $recurringDeposit->accountCustomer->customer;
        $susuScheme = $account->susuScheme;

        return new self(
            recurringDepositPause: $recurringDepositPause,
            recurringDeposit: $recurringDeposit,
            account: $account,
            customer: $customer,
            susuScheme: $susuScheme,
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'RecurringDepositPause',
                'attributes' => [
                    'resource_id' => $this->recurringDepositPause->resource_id,
                    'paused_at' => Carbon::parse($this->recurringDepositPause->paused_at)->toFormattedDateString(),
                    'expires_at' => Carbon::parse($this->recurringDepositPause->expires_at)->toFormattedDateString(),
                    'accepted_terms' => $this->recurringDepositPause->accepted_terms,
                    'status' => $this->recurringDepositPause->status,
                ],
                'included' => [
                    'recurring_deposit' => [
                        'type' => 'RecurringDeposit',
                        'attributes' => [
                            'resource_id' => $this->recurringDeposit->resource_id,
                            'recurring_amount' => $this->recurringDeposit->recurring_amount->getAmount()->__toString(),
                            'initial_amount' => $this->recurringDeposit->initial_amount->getAmount()->__toString(),
                            'initial_frequency' => $this->recurringDeposit->initial_frequency,
                            'start_date' => Carbon::parse($this->recurringDeposit->start_date)->toFormattedDateString(),
                            'end_date' => Carbon::parse($this->recurringDeposit->end_date)->toFormattedDateString(),
                            'rollover_enabled' => $this->recurringDeposit->rollover_enabled,
                            'status' => $this->recurringDeposit->status,
                        ],
                    ],
                    'account' => [
                        'type' => 'Account',
                        'attributes' => [
                            'resource_id' => $this->account->resource_id,
                            'account_name' => $this->account->account_name,
                            'account_number' => $this->account->account_number,
                        ],
                    ],
                    'susu_scheme' => [
                        'type' => 'SusuScheme',
                        'attributes' => [
                            'resource_id' => $this->susuScheme->resource_id,
                            'susu_scheme' => $this->susuScheme->alias,
                        ],
                    ],
                    'customer' => [
                        'type' => 'Customer',
                        'attributes' => [
                            'resource_id' => $this->customer->resource_id,
                            'phone_number' => $this->customer->phone_number,
                        ],
                    ],
                ],
            ],
        ];
    }
}
