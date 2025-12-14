<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Transaction\Models\Transaction;

final readonly class TransactionCreateResponseDTO
{
    public function __construct(
        public Transaction $transaction,
        public Account $account,
        public Customer $customer,
        public Wallet $linked_wallet,
        public bool $is_initial_deposit,
    ) {
        // ..
    }

    public static function fromDomain(
        Transaction $transaction,
        bool $is_initial_deposit
    ): self {
        return new self(
            transaction: $transaction,
            account: $transaction->account,
            customer: $transaction->payment->initiator,
            linked_wallet: $transaction->wallet,
            is_initial_deposit: $is_initial_deposit,
        );
    }

    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'Transaction',
                'attributes' => [
                    'is_initial_deposit' => $this->is_initial_deposit,
                    'resource_id' => $this->transaction->resource_id,
                    'reference_number' => $this->transaction->reference_number,
                    'transaction_category' => $this->transaction->category->code,
                    'amount' => $this->transaction->amount->getAmount()->__toString(),
                    'charge' => $this->transaction->charge->getAmount()->__toString(),
                    'total' => $this->transaction->total->getAmount()->__toString(),
                    'currency' => $this->transaction->total->getCurrency()->getCurrencyCode(),
                    'description' => $this->transaction->description,
                    'date' => $this->transaction->date,
                    'status' => $this->transaction->status,
                ],
                'relationships' => [
                    'account' => [
                        'type' => 'Account',
                        'attributes' => [
                            'resource_id' => $this->account->resource_id,
                            'account_name' => $this->account->account_name,
                            'account_number' => $this->account->account_number,
                            'account_scheme' => $this->account->getSusuScheme()->alias,
                        ],
                    ],
                    'customer' => [
                        'type' => 'Customer',
                        'attributes' => [
                            'resource_id' => $this->customer['resource_id'],
                            'phone_number' => $this->customer['phone_number'],
                        ],
                    ],
                    'wallet' => [
                        'type' => 'Wallet',
                        'attributes' => [
                            'wallet_name' => $this->linked_wallet->wallet_name,
                            'wallet_number' => $this->linked_wallet->wallet_number,
                            'wallet_network' => $this->linked_wallet->network_code,
                        ],
                    ],
                ],
            ],
        ];
    }
}
