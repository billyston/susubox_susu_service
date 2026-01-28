<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Statistics;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Shared\Enums\Statuses;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

final class DailySusuSettlementStatisticsService
{
    protected Collection $settlements;

    protected array $statistics = [];
    protected array $performance = [];

    protected array $insights = [];
    protected array $recommendations = [];

    private Account $account;
    private ?CarbonInterface $from;
    private ?CarbonInterface $to;

    /**
     * @param Account $account
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return $this
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Account $account,
        ?Carbon $from = null,
        ?Carbon $to = null,
    ): self {
        $this->account = $account;
        $this->from = $from;
        $this->to = $to;

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
    public function getPerformance(
    ): array {
        return $this->performance;
    }

    /**
     * @return array
     */
    public function getInsights(
    ): array {
        return $this->insights;
    }

    /**
     * @return array
     */
    public function getRecommendations(
    ): array {
        return $this->recommendations;
    }

    /**
     * @return array
     */
    public function getPeriod(
    ): array {
        return [
            'from' => $this->from?->toDateString(),
            'to' => $this->to?->toDateString(),
        ];
    }

    /**
     * @return void
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    protected function initialize(
    ): void {
        $query = AccountSettlement::query()->where('account_id', $this->account->id)->latest();

        if ($this->from) {
            $query->where('created_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->where('created_at', '<=', $this->to);
        }

        $this->settlements = $query->get();

        $this->buildStatistics();
        $this->buildPerformance();
        $this->buildInsights();
    }

    /**
     * @return void
     * @throws MoneyMismatchException
     */
    protected function buildStatistics(
    ): void {
        $settlements = $this->settlements;

        $completed = $settlements->where('status', Statuses::COMPLETED->value);

        $principalTotal = $this->sumMoney(
            $completed,
            fn (AccountSettlement $s) => $s->principal_amount
        );

        $chargesTotal = $this->sumMoney(
            $completed,
            fn (AccountSettlement $s) => $s->charge_amount
        );

        $totalPaid = $this->sumMoney(
            $completed,
            fn (AccountSettlement $s) => $s->total_amount
        );

        $this->statistics = [
            'counts' => [
                'total' => $settlements->count(),
                'pending' => $settlements->where('status', Statuses::PENDING->value)->count(),
                'processing' => $settlements->where('status', Statuses::PROCESSING->value)->count(),
                'completed' => $completed->count(),
                'failed' => $settlements->where('status', Statuses::FAILED->value)->count(),
                'cancelled' => $settlements->where('status', Statuses::CANCELLED->value)->count(),
            ],
            'amounts' => [
                'principal_total' => $principalTotal,
                'charges_total' => $chargesTotal,
                'total_paid' => $totalPaid,
            ],
            'last_completed' => $completed->sortByDesc('completed_at')->first(),
            'last_failed' => $settlements->where('status', Statuses::FAILED->value)->first(),
        ];
    }

    /**
     * @return void
     */
    protected function buildPerformance(
    ): void {
        $total = $this->statistics['counts']['total'];
        $completed = $this->statistics['counts']['completed'];

        $successRate = $total > 0 ? round($completed / $total * 100, 2) : 0;
        $averageSettlement = $completed > 0 ? $this->statistics['amounts']['total_paid']->dividedBy($completed) : Money::zero('GHS');

        $this->performance = [
            'success_rate' => $successRate,
            'average_settlement_amount' => $averageSettlement,
        ];
    }

    /**
     * @return void
     * @throws UnknownCurrencyException
     */
    protected function buildInsights(
    ): void {
        $completedCount = $this->statistics['counts']['completed'];
        $charges = $this->statistics['amounts']['charges_total'];

        if ($completedCount >= 5) {
            $this->insights[] = [
                'type' => 'positive',
                'message' => 'You have successfully completed multiple settlements.',
            ];
        }

        if (! $charges->isZero() && $charges->isGreaterThan(Money::of(5000, 'GHS'))) {
            $this->insights[] = [
                'type' => 'warning',
                'message' => 'You have paid significant charges on withdrawals.',
            ];

            $this->recommendations[] = 'Consider fewer withdrawals to reduce charges.';
        }

        if ($this->performance['success_rate'] < 70) {
            $this->insights[] = [
                'type' => 'warning',
                'message' => 'Some settlements are failing frequently.',
            ];

            $this->recommendations[] = 'Review your payment instructions or account balance before withdrawing.';
        }
    }

    /**
     * @param Collection $items
     * @param callable $resolver
     * @param string $currency
     * @return Money
     * @throws MoneyMismatchException
     */
    protected function sumMoney(
        Collection $items,
        callable $resolver,
        string $currency = 'GHS'
    ): Money {
        $total = Money::zero($currency);

        foreach ($items as $item) {
            $money = $resolver($item);
            $total = $total->plus($money);
        }

        return $total;
    }
}
