<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs;

use Brick\Money\Money;

final readonly class TransactionCreateDTO
{
    public function __construct(
        public string $resource_id,
        public bool $is_initial_deposit,
        public string $reference_number,
        public string $description,
        public Money $amount,
        public Money $charges,
        public Money $total,
        public string $wallet,
        public string $date,
        public string $transaction_category,
        public string $status_code,
        public string $status,
        public string $account_name,
        public string $account_number,
        public string $wallet_name,
        public string $wallet_number,
        public string $wallet_network,
    ) {
        // ..
    }

    public static function fromArray(array $payload): self
    {
        $data = $payload['data'] ?? [];
        $attributes = $data['attributes'] ?? [];

        $relationships = $payload['relationships'] ?? [];
        $account = $relationships['account'] ?? [];
        $linked_wallet = $relationships['linked_wallet'] ?? [];

        $account_attributes = $account['attributes'] ?? [];
        $wallet_attributes = $linked_wallet['attributes'] ?? [];

        // Compute monetary values
        $amount = Money::of($attributes['amount'] ?? 0, 'GHS');
        $charges = Money::of($attributes['charges'] ?? 0, 'GHS');
        $total = Money::of($attributes['total'] ?? 0, 'GHS');

        return new self(
            resource_id: $data['resource_id'] ?? '',
            is_initial_deposit: $attributes['is_initial_deposit'] ?? false,
            reference_number: $attributes['reference_number'] ?? '',
            description: $attributes['description'] ?? '',
            amount: $amount,
            charges: $charges,
            total: $total,
            wallet: $attributes['wallet'] ?? '',
            date: $attributes['date'] ?? '',
            transaction_category: $attributes['transaction_category'] ?? '',
            status_code: $attributes['status_code'] ?? '',
            status: $attributes['status'] ?? '',
            account_name: $account_attributes['account_name'] ?? '',
            account_number: $account_attributes['account_number'] ?? '',
            wallet_name: $wallet_attributes['wallet_name'] ?? '',
            wallet_number: $wallet_attributes['wallet_number'] ?? '',
            wallet_network: $wallet_attributes['wallet_network'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'resource_id' => $this->resource_id,
            'is_initial_deposit' => $this->is_initial_deposit,
            'reference_number' => $this->reference_number,
            'description' => $this->description,
            'amount' => $this->amount,
            'charges' => $this->charges,
            'total' => $this->total,
            'wallet' => $this->wallet,
            'date' => $this->date,
            'transaction_category' => $this->transaction_category,
            'status_code' => $this->status_code,
            'status' => $this->status,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'wallet_name' => $this->wallet_name,
            'wallet_number' => $this->wallet_number,
            'wallet_network' => $this->wallet_network,
        ];
    }
}
