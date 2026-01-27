<?php

declare(strict_types=1);

namespace App\Application\Transaction\Services\Statistics;

use App\Domain\Account\Models\Account;
use App\Domain\Transaction\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

final class TransactionLoader
{
    /**
     * @param Account $account
     * @param CarbonInterface $fromDate
     * @param CarbonInterface $toDate
     * @return Collection
     */
    public function loadTransactions(
        Account $account,
        CarbonInterface $fromDate,
        CarbonInterface $toDate
    ): Collection {
        return Transaction::where('account_id', $account->id)
            ->whereBetween('date', [$fromDate, $toDate])
            ->with([
                'category:id,code,name',
                'wallet:id,wallet_name,wallet_name,network_code',
            ])
            ->select([
                'id',
                'resource_id',
                'account_id',
                'transaction_category_id',
                'transaction_type',
                'wallet_id',
                'reference_number',
                'amount',
                'charge',
                'total',
                'currency',
                'description',
                'narration',
                'date',
                'status_code',
                'status',
                'extra_data',
                'created_at',
            ])
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($transaction) {
                $transaction->date = Carbon::parse($transaction->date);
                return $transaction;
            });
    }
}
