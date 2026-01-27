<?php

declare(strict_types=1);

namespace App\Application\Transaction\Services\Statistics;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Models\Transaction;
use Brick\Math\RoundingMode;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

final class AccountTransactionStatsService
{
    private Account $account;
    private ?CarbonInterface $fromDate;
    private ?CarbonInterface $toDate;
    private Collection $transactions;
    private array $statistics = [];
    private array $performance = [];
    private array $comparisons = [];

    private TransactionLoader $transactionLoader;
    private StatisticsCalculator $statisticsCalculator;
    private PerformanceReportGenerator $performanceReportGenerator;
    private MoneyCalculator $moneyCalculator;

    /**
     * @param TransactionLoader $transactionLoader
     * @param StatisticsCalculator $statisticsCalculator
     * @param PerformanceReportGenerator $performanceReportGenerator
     * @param MoneyCalculator $moneyCalculator
     */
    public function __construct(
        TransactionLoader $transactionLoader,
        StatisticsCalculator $statisticsCalculator,
        PerformanceReportGenerator $performanceReportGenerator,
        MoneyCalculator $moneyCalculator
    ) {
        $this->transactionLoader = $transactionLoader;
        $this->statisticsCalculator = $statisticsCalculator;
        $this->performanceReportGenerator = $performanceReportGenerator;
        $this->moneyCalculator = $moneyCalculator;
    }

    /**
     * @param Account $account
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return $this
     * @throws MoneyMismatchException
     */
    public function execute(
        Account $account,
        ?Carbon $from = null,
        ?Carbon $to = null,
    ): self {
        $this->account = $account;
        $this->fromDate = $from ?? Carbon::now()->startOfMonth();
        $this->toDate = $to ?? Carbon::now()->endOfMonth();

        $this->initialize();

        return $this;
    }

    /**
     * @return array
     */
    public function getStatistics(
    ): array {
        return $this->statistics;
    }

    /**
     * @return array
     */
    public function getPerformanceReport(
    ): array {
        return $this->performance;
    }

    /**
     * @return array
     */
    public function getComparisons(
    ): array {
        return $this->comparisons;
    }

    /**
     * @return Collection
     */
    public function getTransactions(
    ): Collection {
        return $this->transactions;
    }

    /**
     * @return array
     */
    public function getPeriod(
    ): array {
        return [
            'from' => $this->fromDate,
            'to' => $this->toDate,
        ];
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        $statistics = $this->statistics;
        $comparisons = $this->comparisons;

        // Convert Money objects to strings
        $convertMoneyToString = function (&$value) use (&$convertMoneyToString) {
            if ($value instanceof Money) {
                $value = $this->moneyCalculator->formatMoney($value);
            } elseif (is_array($value)) {
                array_walk_recursive($value, $convertMoneyToString);
            }
        };

        array_walk_recursive($statistics, $convertMoneyToString);
        array_walk_recursive($comparisons, $convertMoneyToString);

        return [
            'metadata' => [
                'generated_at' => now()->toISOString(),
                'period' => $this->getPeriod(),
                'account' => [
                    'id' => $this->account->id,
                    'account_number' => $this->account->account_number,
                ],
            ],
            'statistics' => $statistics,
            'performance_report' => $this->performance,
            'comparisons' => $comparisons,
        ];
    }

    /**
     * @return array
     */
    public function getDashboardMetrics(
    ): array {
        $topCategory = collect($this->statistics['categories'])
            ->sortByDesc('total_amount_money')
            ->first();

        return [
            'total_transactions' => $this->statistics['basic']['total'],
            'success_rate' => $this->statistics['basic']['success_rate'],
            'total_volume' => $this->statistics['financial']['total_volume'],
            'net_flow' => $this->statistics['financial']['net_flow'],
            'total_charges' => $this->statistics['fees']['total_charges'],
            'performance_score' => $this->performanceReportGenerator->calculatePerformanceScore($this->statistics),
            'performance_grade' => $this->performanceReportGenerator->getPerformanceGrade($this->statistics),
            'top_category' => $topCategory['name'] ?? 'N/A',
        ];
    }

    /**
     * @return array
     */
    public function getCategoryMetrics(
    ): array {
        $categories = $this->statistics['categories'];

        array_walk_recursive($categories, function (&$value) {
            if ($value instanceof Money) {
                $value = $this->moneyCalculator->formatMoney($value);
            }
        });

        return $categories;
    }

    /**
     * @return array
     */
    public function getTimeSeriesData(
    ): array {
        $data = [
            'daily' => $this->statistics['time_based']['daily'],
            'weekly' => $this->statistics['time_based']['weekly'],
            'monthly' => $this->statistics['time_based']['monthly'],
        ];

        array_walk_recursive($data, function (&$value) {
            if ($value instanceof Money) {
                $value = $this->moneyCalculator->formatMoney($value);
            }
        });

        return $data;
    }

    /**
     * @return void
     * @throws MoneyMismatchException
     */
    private function initialize(
    ): void {
        $this->loadTransactions();
        $this->calculateAllStatistics();
        $this->generatePerformanceReport();
    }

    /**
     * @return void
     */
    private function loadTransactions(
    ): void {
        $this->transactions = $this->transactionLoader->loadTransactions(
            $this->account,
            $this->fromDate,
            $this->toDate
        );
    }

    /**
     * @return void
     * @throws MoneyMismatchException
     */
    private function calculateAllStatistics(
    ): void {
        $this->statistics['basic'] = $this->statisticsCalculator->calculateBasicStats($this->transactions);
        $this->statistics['financial'] = $this->statisticsCalculator->calculateFinancialStats($this->transactions);
        $this->statistics['categories'] = $this->statisticsCalculator->calculateCategoryStats($this->transactions);
        $this->statistics['time_based'] = $this->statisticsCalculator->calculateTimeBasedStats($this->transactions);
        $this->statistics['statuses'] = $this->calculateStatusStats();
        $this->statistics['fees'] = $this->calculateFeeStats();
        $this->statistics['wallets'] = $this->calculateWalletStats();
        $this->statistics['trends'] = $this->calculateTrendAnalysis();
        $this->calculateComparisonStats();
    }

    /**
     * @return array
     */
    private function calculateStatusStats(
    ): array {
        $statusGroups = $this->transactions->groupBy('status');

        return $statusGroups->map(function ($group) {
            $totalAmount = $this->moneyCalculator->sumMoney($group, 'amount');
            $avgAmount = $this->moneyCalculator->averageMoney($group, 'amount');

            $creditAmount = $this->moneyCalculator->sumMoney(
                $group->where('transaction_type', 'credit'),
                'amount'
            );

            $debitAmount = $this->moneyCalculator->sumMoney(
                $group->where('transaction_type', 'debit'),
                'amount'
            );

            return [
                'count' => $group->count(),
                'percentage' => round($group->count() / $this->transactions->count() * 100, 2),
                'total_amount' => $this->moneyCalculator->formatMoney($totalAmount),
                'total_amount_money' => $totalAmount,
                'average_amount' => $avgAmount ? $this->moneyCalculator->formatMoney($avgAmount) : '0.00',
                'average_amount_money' => $avgAmount,
                'breakdown' => [
                    'credits' => $this->moneyCalculator->formatMoney($creditAmount),
                    'credits_money' => $creditAmount,
                    'debits' => $this->moneyCalculator->formatMoney($debitAmount),
                    'debits_money' => $debitAmount,
                ],
            ];
        })->toArray();
    }

    /**
     * @return array
     * @throws MoneyMismatchException
     */
    private function calculateFeeStats(
    ): array {
        $successful = $this->transactions->where('status', Statuses::SUCCESS->value);

        $totalCharges = $this->moneyCalculator->sumMoney($successful, 'charge');
        $totalAmount = $this->moneyCalculator->sumMoney($successful, 'amount');
        $avgCharge = $this->moneyCalculator->averageMoney($successful, 'charge');

        $chargeToAmountRatio = 0;
        $totalAmountValue = $totalAmount->getAmount()->toFloat();
        if ($totalAmountValue > 0) {
            $totalChargesValue = $totalCharges->getAmount()->toFloat();
            $chargeToAmountRatio = round($totalChargesValue / $totalAmountValue * 100, 2);
        }

        $averageChargeRate = round($successful->avg(function ($txn) {
            $amountValue = $txn->amount->getAmount()->toFloat();
            if ($amountValue > 0) {
                $chargeValue = $txn->charge->getAmount()->toFloat();
                return $chargeValue / $amountValue * 100;
            }
            return 0;
        }) ?? 0, 2);

        return [
            'total_charges' => $this->moneyCalculator->formatMoney($totalCharges),
            'total_charges_money' => $totalCharges,
            'total_with_charges' => $this->moneyCalculator->formatMoney($totalCharges->plus($totalAmount)),
            'total_with_charges_money' => $totalCharges->plus($totalAmount),
            'average_charge' => $avgCharge ? $this->moneyCalculator->formatMoney($avgCharge) : '0.00',
            'average_charge_money' => $avgCharge,
            'charge_to_amount_ratio' => $chargeToAmountRatio,
            'average_charge_rate' => $averageChargeRate,
            'breakdown_by_category' => $this->calculateFeeBreakdownByCategory(),
        ];
    }

    /**
     * @return array
     * @throws MoneyMismatchException
     */
    private function calculateWalletStats(
    ): array {
        $successful = $this->transactions->where('status', Statuses::SUCCESS->value);
        $walletGroups = $successful->groupBy('wallet_id');

        $totalVolume = $this->moneyCalculator->sumMoney($successful, 'amount');
        $totalVolumeValue = $totalVolume->getAmount()->toFloat();

        return $walletGroups->map(function ($walletTxns, $walletId) use ($totalVolumeValue) {
            $wallet = $walletTxns->first()->wallet;

            $total = $this->moneyCalculator->sumMoney($walletTxns, 'amount');
            $avg = $this->moneyCalculator->averageMoney($walletTxns, 'amount');

            $credits = $this->moneyCalculator->sumMoney(
                $walletTxns->where('transaction_type', 'credit'),
                'amount'
            );

            $debits = $this->moneyCalculator->sumMoney(
                $walletTxns->where('transaction_type', 'debit'),
                'amount'
            );

            $totalValue = $total->getAmount()->toFloat();
            $percentageOfTotal = $totalVolumeValue > 0
                ? round($totalValue / $totalVolumeValue * 100, 2)
                : 0;

            return [
                'id' => $walletId,
                'name' => $wallet->wallet_name ?? 'Unknown',
                'network' => $wallet->network_code ?? 'unknown',
                'count' => $walletTxns->count(),
                'total' => $this->moneyCalculator->formatMoney($total),
                'total_money' => $total,
                'average' => $avg ? $this->moneyCalculator->formatMoney($avg) : '0.00',
                'average_money' => $avg,
                'credits' => $this->moneyCalculator->formatMoney($credits),
                'credits_money' => $credits,
                'debits' => $this->moneyCalculator->formatMoney($debits),
                'debits_money' => $debits,
                'percentage_of_total' => $percentageOfTotal,
            ];
        })->sortByDesc('total_money')->values()->toArray();
    }

    /**
     * @return array[]
     * @throws MoneyMismatchException
     */
    private function calculateTrendAnalysis(
    ): array {
        $successful = $this->transactions->where('status', Statuses::SUCCESS->value);

        // Get previous period for comparison
        $periodLength = $this->fromDate->diffInDays($this->toDate);
        $prevFrom = $this->fromDate->copy()->subDays($periodLength + 1);
        $prevTo = $this->fromDate->copy()->subDay();

        $prevTransactions = Transaction::where('account_id', $this->account->id)
            ->whereBetween('date', [$prevFrom, $prevTo])
            ->where('status', Statuses::SUCCESS->value)
            ->get()
            ->map(function ($transaction) {
                $transaction->date = Carbon::parse($transaction->date);
                return $transaction;
            });

        $currentVolume = $this->moneyCalculator->sumMoney($successful, 'amount');
        $previousVolume = $this->moneyCalculator->sumMoney($prevTransactions, 'amount');

        $currentCount = $successful->count();
        $previousCount = $prevTransactions->count();

        $volumeChange = 0;
        $previousVolumeValue = $previousVolume->getAmount()->toFloat();
        if ($previousVolumeValue > 0) {
            $currentVolumeValue = $currentVolume->getAmount()->toFloat();
            $volumeChange = round(($currentVolumeValue - $previousVolumeValue) / $previousVolumeValue * 100, 2);
        } elseif ($currentVolume->isGreaterThan(0)) {
            $volumeChange = 100;
        }

        $countChange = 0;
        if ($previousCount > 0) {
            $countChange = round(($currentCount - $previousCount) / $previousCount * 100, 2);
        } elseif ($currentCount > 0) {
            $countChange = 100;
        }

        $currentAvgValue = $currentCount > 0
            ? $currentVolume->dividedBy($currentCount, RoundingMode::HALF_UP)
            : Money::zero('GHS');

        $previousAvgValue = $previousCount > 0
            ? $previousVolume->dividedBy($previousCount, RoundingMode::HALF_UP)
            : Money::zero('GHS');

        $avgValueChange = 0;
        $previousAvgValueFloat = $previousAvgValue->getAmount()->toFloat();
        if ($previousAvgValueFloat > 0) {
            $currentAvgValueFloat = $currentAvgValue->getAmount()->toFloat();
            $avgValueChange = round(($currentAvgValueFloat - $previousAvgValueFloat) / $previousAvgValueFloat * 100, 2);
        }

        return [
            'volume' => [
                'current' => $this->moneyCalculator->formatMoney($currentVolume),
                'current_money' => $currentVolume,
                'previous' => $this->moneyCalculator->formatMoney($previousVolume),
                'previous_money' => $previousVolume,
                'change' => $volumeChange,
                'direction' => $currentVolume->isGreaterThan($previousVolume) ? 'up' :
                    ($currentVolume->isLessThan($previousVolume) ? 'down' : 'stable'),
            ],
            'transaction_count' => [
                'current' => $currentCount,
                'previous' => $previousCount,
                'change' => $countChange,
                'direction' => $currentCount > $previousCount ? 'up' :
                    ($currentCount < $previousCount ? 'down' : 'stable'),
            ],
            'average_value' => [
                'current' => $currentCount > 0 ? $this->moneyCalculator->formatMoney($currentAvgValue) : '0.00',
                'current_money' => $currentAvgValue,
                'previous' => $previousCount > 0 ? $this->moneyCalculator->formatMoney($previousAvgValue) : '0.00',
                'previous_money' => $previousAvgValue,
                'change' => $avgValueChange,
            ],
        ];
    }

    /**
     * @return void
     * @throws MoneyMismatchException
     */
    private function calculateComparisonStats(
    ): void {
        $successful = $this->transactions->where('status', Statuses::SUCCESS->value);

        $accountAvg = Transaction::where('account_id', $this->account->id)
            ->where('status', Statuses::SUCCESS->value)
            ->where('date', '<', $this->fromDate)
            ->get()
            ->map(function ($transaction) {
                $transaction->date = Carbon::parse($transaction->date);
                return $transaction;
            });

        $historicalCount = $accountAvg->count();
        $historicalVolume = $this->moneyCalculator->sumMoney($accountAvg, 'amount');
        $historicalAvgAmount = $historicalCount > 0
            ? $historicalVolume->dividedBy($historicalCount, RoundingMode::HALF_UP)
            : Money::zero('GHS');

        $historicalChargeRate = 0;
        $historicalVolumeValue = $historicalVolume->getAmount()->toFloat();
        if ($historicalVolumeValue > 0) {
            $historicalCharges = $this->moneyCalculator->sumMoney($accountAvg, 'charge');
            $historicalChargesValue = $historicalCharges->getAmount()->toFloat();
            $historicalChargeRate = round($historicalChargesValue / $historicalVolumeValue * 100, 2);
        }

        $currentAvgAmount = $successful->count() > 0
            ? $this->moneyCalculator->sumMoney($successful, 'amount')->dividedBy($successful->count(), RoundingMode::HALF_UP)
            : Money::zero('GHS');

        $currentChargeRate = $this->statistics['fees']['charge_to_amount_ratio'];

        $countDifference = 0;
        if ($historicalCount > 0) {
            $countDifference = round(($successful->count() - $historicalCount) / $historicalCount * 100, 2);
        }

        $amountDifference = 0;
        $historicalAvgAmountValue = $historicalAvgAmount->getAmount()->toFloat();
        if ($historicalAvgAmountValue > 0) {
            $currentAvgAmountValue = $currentAvgAmount->getAmount()->toFloat();
            $amountDifference = round(($currentAvgAmountValue - $historicalAvgAmountValue) / $historicalAvgAmountValue * 100, 2);
        }

        $chargeRateDifference = 0;
        if ($historicalChargeRate > 0) {
            $chargeRateDifference = round(($currentChargeRate - $historicalChargeRate) / $historicalChargeRate * 100, 2);
        }

        $this->comparisons = [
            'vs_account_average' => [
                'transaction_count' => [
                    'current' => $successful->count(),
                    'average' => $historicalCount,
                    'difference' => $countDifference,
                ],
                'average_amount' => [
                    'current' => $this->moneyCalculator->formatMoney($currentAvgAmount),
                    'current_money' => $currentAvgAmount,
                    'average' => $this->moneyCalculator->formatMoney($historicalAvgAmount),
                    'average_money' => $historicalAvgAmount,
                    'difference' => $amountDifference,
                ],
                'charge_rate' => [
                    'current' => $currentChargeRate,
                    'average' => $historicalChargeRate,
                    'difference' => $chargeRateDifference,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    private function calculateFeeBreakdownByCategory(
    ): array {
        $successful = $this->transactions->where('status', Statuses::SUCCESS->value);

        return collect(TransactionCategoryCode::cases())->mapWithKeys(function ($categoryCode) use ($successful) {
            $categoryTxns = $successful->filter(function ($txn) use ($categoryCode) {
                return optional($txn->category)->code === $categoryCode->value;
            });

            $totalCharges = $this->moneyCalculator->sumMoney($categoryTxns, 'charge');
            $totalAmount = $this->moneyCalculator->sumMoney($categoryTxns, 'amount');
            $avgCharge = $this->moneyCalculator->averageMoney($categoryTxns, 'charge');

            $chargeRate = 0;
            $totalAmountValue = $totalAmount->getAmount()->toFloat();
            if ($totalAmountValue > 0) {
                $totalChargesValue = $totalCharges->getAmount()->toFloat();
                $chargeRate = round($totalChargesValue / $totalAmountValue * 100, 2);
            }

            return [
                $categoryCode->value => [
                    'total_charges' => $this->moneyCalculator->formatMoney($totalCharges),
                    'total_charges_money' => $totalCharges,
                    'avg_charge' => $avgCharge ? $this->moneyCalculator->formatMoney($avgCharge) : '0.00',
                    'avg_charge_money' => $avgCharge,
                    'charge_rate' => $chargeRate,
                ],
            ];
        })->toArray();
    }

    /**
     * @return void
     */
    private function generatePerformanceReport(
    ): void {
        $this->performance = $this->performanceReportGenerator->generate(
            $this->account,
            $this->statistics,
            $this->fromDate,
            $this->toDate
        );
    }
}
