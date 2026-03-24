<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\DTOs\RecurringDeposit;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Models\SusuScheme;

final readonly class RecurringDepositResponseDTO
{
    /**
     * @param bool $isSuccessful
     * @param RecurringDeposit $recurringDeposit
     * @param Account $account
     * @param Customer $customer
     * @param Wallet $wallet
     * @param SusuScheme $susuScheme
     */
    public function __construct(
        public ?bool $isSuccessful,
        public RecurringDeposit $recurringDeposit,
        public Account $account,
        public Customer $customer,
        public Wallet $wallet,
        public SusuScheme $susuScheme,
    ) {
        // ..
    }

    /**
     * @param RecurringDeposit $recurringDeposit
     * @param bool $isSuccessful
     * @return self
     */
    public static function fromDomain(
        RecurringDeposit $recurringDeposit,
        ?bool $isSuccessful = null,
    ): self {
        // Extract the main resources
        $account = $recurringDeposit->account;
        $customer = $account->accountCustomer->customer;
        $wallet = $account->accountCustomer->wallet;
        $susuScheme = $account->susuScheme;

        return new self(
            isSuccessful: $isSuccessful,
            recurringDeposit: $recurringDeposit,
            account: $account,
            customer: $customer,
            wallet: $wallet,
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
                'type' => 'RecurringDeposit',
                'attributes' => [
                    'is_successful' => $this->isSuccessful,
                    'resource_id' => $this->recurringDeposit->resource_id,
                    'recurring_amount' => $this->recurringDeposit->recurring_amount->getAmount()->__toString(),
                    'initial_amount' => $this->recurringDeposit->initial_amount->getAmount()->__toString(),
                    'initial_frequency' => $this->recurringDeposit->initial_frequency,
                    'start_date' => $this->recurringDeposit->start_date,
                    'end_date' => $this->recurringDeposit->end_date,
                    'rollover_enabled' => $this->recurringDeposit->rollover_enabled,
                    'status' => $this->recurringDeposit->status,
                ],
                'included' => [
                    'customer' => [
                        'type' => 'Customer',
                        'attributes' => [
                            'resource_id' => $this->customer->resource_id,
                            'phone_number' => $this->customer->phone_number,
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
                    'wallet' => [
                        'type' => 'Wallet',
                        'attributes' => [
                            'resource_id' => $this->wallet->resource_id,
                            'wallet_name' => $this->wallet->wallet_name,
                            'wallet_number' => $this->wallet->wallet_number,
                            'wallet_network' => $this->wallet->network_code,
                        ],
                    ],
                    'susu_scheme' => [
                        'type' => 'SusuScheme',
                        'attributes' => [
                            'resource_id' => $this->susuScheme->resource_id,
                            'susu_scheme_alias' => $this->susuScheme->alias,
                        ],
                    ],
                ],
            ],
        ];
    }
}
