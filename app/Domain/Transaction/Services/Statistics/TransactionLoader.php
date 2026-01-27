<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services\Statistics;

use App\Domain\Account\Models\Account;
use App\Domain\Transaction\Models\Transaction;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class TransactionLoader
{
    public function load(
        Account $account,
        CarbonInterface $from,
        CarbonInterface $to
    ): Collection {
        return Transaction::where('account_id', $account->id)
            ->whereBetween('date', [$from, $to])
            ->with(['category:id,code,name', 'wallet:id,wallet_name,network_code'])
            ->orderBy('date', 'desc')
            ->get()
            ->map(fn ($t) => tap($t, fn ($x) => $x->date = Carbon::parse($x->date)));
    }
}

