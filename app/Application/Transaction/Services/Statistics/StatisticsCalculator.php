<?php

declare(strict_types=1);

namespace App\Application\Transaction\Services\Statistics;

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Models\TransactionCategory;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class StatisticsCalculator
{
    private MoneyCalculator $moneyCalculator;

    /**
     * @param MoneyCalculator $moneyCalculator
     */
    public function __construct(
        MoneyCalculator $moneyCalculator
    ) {
        $this->moneyCalculator = $moneyCalculator;
    }

    /**
     * @param Collection $transactions
     * @return array
     */
    public function calculateBasicStats(
        Collection $transactions
    ): array {
        $successfulCount = $transactions->where('status', Statuses::SUCCESS->value)->count();
        $totalCount = $transactions->count();

        return [
            'total' => $totalCount,
            'successful' => $successfulCount,
            'failed' => $transactions->where('status', Statuses::FAILED->value)->count(),
            'reversed' => $transactions->where('status', Statuses::REVERSED->value)->count(),
            'refunded' => $transactions->where('status', Statuses::REFUNDED->value)->count(),
            'cancelled' => $transactions->where('status', Statuses::CANCELLED->value)->count(),
            'success_rate' => $totalCount > 0 ? round($successfulCount / $totalCount * 100, 2) : 0,
        ];
    }

    /**
     * @throws MoneyMismatchException
     */
    public function calculateFinancialStats(
        Collection $transactions
    ): array {
        $successful = $transactions->where('status', Statuses::SUCCESS->value);
        $credits = $successful->where('transaction_type', 'credit');
        $debits = $successful->where('transaction_type', 'debit');

        $creditAmount = $this->moneyCalculator->sumMoney($credits, 'amount');
        $debitAmount = $this->moneyCalculator->sumMoney($debits, 'amount');
        $creditAvg = $this->moneyCalculator->averageMoney($credits, 'amount');
        $debitAvg = $this->moneyCalculator->averageMoney($debits, 'amount');

        // Calculate min/max
        $creditMin = $credits->isNotEmpty() ? $credits->min('amount') : Money::zero('GHS');
        $creditMax = $credits->isNotEmpty() ? $credits->max('amount') : Money::zero('GHS');
        $debitMin = $debits->isNotEmpty() ? $debits->min('amount') : Money::zero('GHS');
        $debitMax = $debits->isNotEmpty() ? $debits->max('amount') : Money::zero('GHS');
        $creditMedian = $this->moneyCalculator->calculateMoneyMedian($credits, 'amount');
        $debitMedian = $this->moneyCalculator->calculateMoneyMedian($debits, 'amount');

        // Calculate turnover ratio
        $turnoverRatio = 0;

        if ($debitAmount->isGreaterThan(0)) {
            $debitValue = $debitAmount->getAmount()->toFloat();
            if ($debitValue > 0) {
                $creditValue = $creditAmount->getAmount()->toFloat();
                $turnoverRatio = round($creditValue / $debitValue * 100, 2);
            }
        } elseif ($creditAmount->isGreaterThan(0)) {
            $turnoverRatio = 100;
        }

        return [
            'credits' => [
                'count' => $credits->count(),
                'total' => $this->moneyCalculator->formatMoney($creditAmount),
                'total_money' => $creditAmount,
                'average' => $creditAvg ? $this->moneyCalculator->formatMoney($creditAvg) : '0.00',
                'average_money' => $creditAvg,
                'min' => $this->moneyCalculator->formatMoney($creditMin),
                'min_money' => $creditMin,
                'max' => $this->moneyCalculator->formatMoney($creditMax),
                'max_money' => $creditMax,
                'median' => number_format($creditMedian, 2, '.', ''),
            ],
            'debits' => [
                'count' => $debits->count(),
                'total' => $this->moneyCalculator->formatMoney($debitAmount),
                'total_money' => $debitAmount,
                'average' => $debitAvg ? $this->moneyCalculator->formatMoney($debitAvg) : '0.00',
                'average_money' => $debitAvg,
                'min' => $this->moneyCalculator->formatMoney($debitMin),
                'min_money' => $debitMin,
                'max' => $this->moneyCalculator->formatMoney($debitMax),
                'max_money' => $debitMax,
                'median' => number_format($debitMedian, 2, '.', ''),
            ],
            'net_flow' => $this->moneyCalculator->formatMoney($creditAmount->minus($debitAmount)),
            'net_flow_money' => $creditAmount->minus($debitAmount),
            'total_volume' => $this->moneyCalculator->formatMoney($creditAmount->plus($debitAmount)),
            'total_volume_money' => $creditAmount->plus($debitAmount),
            'turnover_ratio' => $turnoverRatio,
        ];
    }

    /**
     * @param Collection $transactions
     * @return array
     * @throws MoneyMismatchException
     */
    public function calculateCategoryStats(
        Collection $transactions
    ): array {
        $categoryStats = [];
        $successful = $transactions->where('status', Statuses::SUCCESS->value);
        $totalSuccessful = $successful->count();
        $totalAmount = $this->moneyCalculator->sumMoney($successful, 'amount');
        $totalAmountValue = $totalAmount->getAmount()->toFloat();

        foreach (TransactionCategoryCode::cases() as $categoryCode) {
            $categoryTxns = $transactions->filter(function ($txn) use ($categoryCode) {
                return optional($txn->category)->code === $categoryCode->value;
            });

            $successfulCategory = $successful->filter(function ($txn) use ($categoryCode) {
                return optional($txn->category)->code === $categoryCode->value;
            });

            $categoryAmount = $this->moneyCalculator->sumMoney($successfulCategory, 'amount');
            $categoryAvg = $this->moneyCalculator->averageMoney($successfulCategory, 'amount');

            $categoryCredits = $this->moneyCalculator->sumMoney(
                $successfulCategory->where('transaction_type', 'credit'),
                'amount'
            );

            $categoryDebits = $this->moneyCalculator->sumMoney(
                $successfulCategory->where('transaction_type', 'debit'),
                'amount'
            );

            $categoryStats[$categoryCode->value] = [
                'name' => TransactionCategory::where('code', $categoryCode->value)->first()?->name ?? $categoryCode->value,
                'count' => $categoryTxns->count(),
                'successful' => $successfulCategory->count(),
                'failed' => $categoryTxns->where('status', Statuses::FAILED->value)->count(),
                'total_amount' => $this->moneyCalculator->formatMoney($categoryAmount),
                'total_amount_money' => $categoryAmount,
                'average_amount' => $categoryAvg ? $this->moneyCalculator->formatMoney($categoryAvg) : '0.00',
                'average_amount_money' => $categoryAvg,
                'success_rate' => $categoryTxns->count() > 0 ? round($successfulCategory->count() / $categoryTxns->count() * 100, 2) : 0,
                'breakdown' => [
                    'credits' => $this->moneyCalculator->formatMoney($categoryCredits),
                    'credits_money' => $categoryCredits,
                    'debits' => $this->moneyCalculator->formatMoney($categoryDebits),
                    'debits_money' => $categoryDebits,
                ],
            ];
        }

        // Calculate percentages
        foreach ($categoryStats as &$stats) {
            $stats['percentage_of_total'] = $totalSuccessful > 0
                ? round($stats['successful'] / $totalSuccessful * 100, 2)
                : 0;

            $categoryAmountValue = $stats['total_amount_money']->getAmount()->toFloat();
            $stats['percentage_of_volume'] = $totalAmountValue > 0
                ? round($categoryAmountValue / $totalAmountValue * 100, 2)
                : 0;
        }

        return $categoryStats;
    }

    /**
     * @param Collection $transactions
     * @return array
     */
    public function calculateTimeBasedStats(
        Collection $transactions
    ): array {
        $successful = $transactions->where('status', Statuses::SUCCESS->value);

        // Daily breakdown
        $daily = $successful->groupBy(function ($txn) {
            return Carbon::parse($txn->date)->format('Y-m-d');
        })->map(function ($dayTxns) {
            $total = $this->moneyCalculator->sumMoney($dayTxns, 'amount');
            $avg = $this->moneyCalculator->averageMoney($dayTxns, 'amount');

            $creditTotal = $this->moneyCalculator->sumMoney(
                $dayTxns->where('transaction_type', 'credit'),
                'amount'
            );

            $debitTotal = $this->moneyCalculator->sumMoney(
                $dayTxns->where('transaction_type', 'debit'),
                'amount'
            );

            $firstDate = Carbon::parse($dayTxns->first()->date);

            return [
                'date' => $firstDate->format('Y-m-d'),
                'count' => $dayTxns->count(),
                'total' => $this->moneyCalculator->formatMoney($total),
                'total_money' => $total,
                'average' => $avg ? $this->moneyCalculator->formatMoney($avg) : '0.00',
                'average_money' => $avg,
                'credits' => $this->moneyCalculator->formatMoney($creditTotal),
                'credits_money' => $creditTotal,
                'debits' => $this->moneyCalculator->formatMoney($debitTotal),
                'debits_money' => $debitTotal,
            ];
        })->sortBy('date')->values();

        // Weekly breakdown
        $weekly = $successful->groupBy(function ($txn) {
            return Carbon::parse($txn->date)->startOfWeek()->format('Y-W');
        })->map(function ($weekTxns) {
            $total = $this->moneyCalculator->sumMoney($weekTxns, 'amount');
            $avg = $this->moneyCalculator->averageMoney($weekTxns, 'amount');

            $firstDate = Carbon::parse($weekTxns->first()->date);

            return [
                'week' => $firstDate->startOfWeek()->format('Y-m-d'),
                'count' => $weekTxns->count(),
                'total' => $this->moneyCalculator->formatMoney($total),
                'total_money' => $total,
                'average' => $avg ? $this->moneyCalculator->formatMoney($avg) : '0.00',
                'average_money' => $avg,
            ];
        })->sortBy('week')->values();

        // Monthly breakdown
        $monthly = $successful->groupBy(function ($txn) {
            return Carbon::parse($txn->date)->format('Y-m');
        })->map(function ($monthTxns) {
            $total = $this->moneyCalculator->sumMoney($monthTxns, 'amount');
            $avg = $this->moneyCalculator->averageMoney($monthTxns, 'amount');

            $firstDate = Carbon::parse($monthTxns->first()->date);

            return [
                'month' => $firstDate->format('Y-m'),
                'count' => $monthTxns->count(),
                'total' => $this->moneyCalculator->formatMoney($total),
                'total_money' => $total,
                'average' => $avg ? $this->moneyCalculator->formatMoney($avg) : '0.00',
                'average_money' => $avg,
            ];
        })->sortBy('month')->values();

        // Time of day analysis
        $hourly = $successful->groupBy(function ($txn) {
            return Carbon::parse($txn->date)->hour;
        })->map(function ($hourTxns, $hour) {
            $total = $this->moneyCalculator->sumMoney($hourTxns, 'amount');

            return [
                'hour' => $hour,
                'count' => $hourTxns->count(),
                'total' => $this->moneyCalculator->formatMoney($total),
                'total_money' => $total,
            ];
        })->sortBy('hour')->values();

        return [
            'daily' => $daily,
            'weekly' => $weekly,
            'monthly' => $monthly,
            'hourly' => $hourly,
            'peak_day' => $daily->sortByDesc('total_money')->first(),
            'peak_week' => $weekly->sortByDesc('total_money')->first(),
            'peak_hour' => $hourly->sortByDesc('count')->first(),
            'consistency_score' => $this->calculateConsistencyScore($daily),
            'days_active' => $daily->count(),
            'avg_daily_transactions' => round($daily->avg('count') ?? 0, 2),
            'avg_daily_volume' => round($daily->avg(function ($day) {
                return $day['total_money']->getAmount()->toFloat();
            }) ?? 0, 2),
        ];
    }

    /**
     * @param Collection $dailyData
     * @return float
     */
    private function calculateConsistencyScore(
        Collection $dailyData
    ): float {
        if ($dailyData->count() < 2) {
            return 100;
        }

        $dailyCounts = $dailyData->pluck('count')->toArray();
        $mean = array_sum($dailyCounts) / count($dailyCounts);

        if ($mean === 0) {
            return 100;
        }

        $variance = array_sum(array_map(function ($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $dailyCounts)) / count($dailyCounts);

        $cv = sqrt($variance) / $mean * 100;

        return round(max(0, 100 - $cv), 2);
    }
}
