<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Statistics;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Shared\Enums\Statuses;
use Brick\Math\RoundingMode;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

final class DailySusuCycleStatisticsService
{
    protected Collection $cycles;
    protected array $insights;
    protected array $recommendations;

    private Account $account;
    private ?CarbonInterface $from;
    private ?CarbonInterface $to;
    private array $statistics;
    private array $performance;

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
        $this->from = $from;
        $this->to = $to;

        $this->initialize();

        return $this;
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
     * @return void
     * @throws MoneyMismatchException
     */
    protected function initialize(
    ): void {
        $query = AccountCycle::query()
            ->where('account_id', $this->account->id)
            ->orderBy('cycle_number');

        if ($this->from) {
            $query->where('started_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->where('started_at', '<=', $this->to);
        }

        $this->cycles = $query->get();

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
        $cycles = $this->cycles;

        $currentCycle = $cycles->firstWhere('status', Statuses::ACTIVE->value);

        $expectedAmount = $this->sumMoney(
            $cycles,
            fn (AccountCycle $cycle) => $cycle->expected_amount
        );

        $contributedAmount = $this->sumMoney(
            $cycles,
            fn (AccountCycle $cycle) => $cycle->contributed_amount
        );

        $outstandingAmount = $expectedAmount->minus($contributedAmount);

        $expectedFrequencies = $cycles->sum('expected_frequencies');
        $completedFrequencies = $cycles->sum('completed_frequencies');

        $amountCompletionRate = $expectedAmount->isZero() ? 0 : round(
            $contributedAmount
                ->getAmount()
                ->dividedBy($expectedAmount->getAmount(), 4, RoundingMode::HALF_UP)
                ->toFloat() * 100,
            2
        );

        $frequencyCompletionRate = $expectedFrequencies > 0 ? round($completedFrequencies / $expectedFrequencies * 100, 2) : 0;

        $this->statistics = [
            'cycles' => [
                'total' => $cycles->count(),
                'active' => $cycles->where('status', Statuses::ACTIVE->value)->count(),
                'completed' => $cycles->where('status', Statuses::COMPLETED->value)->count(),
                'settled' => $cycles->where('status', Statuses::SETTLED->value)->count(),
                'rolled_over' => $cycles->where('status', Statuses::ROLLED_OVER->value)->count(),
            ],

            'amounts' => [
                'expected' => $expectedAmount,
                'contributed' => $contributedAmount,
                'outstanding' => $outstandingAmount->isNegative() ? Money::zero($expectedAmount->getCurrency()) : $outstandingAmount,
                'completion_rate' => $amountCompletionRate,
            ],

            'frequencies' => [
                'expected' => $expectedFrequencies,
                'completed' => $completedFrequencies,
                'remaining' => max(0, $expectedFrequencies - $completedFrequencies),
                'completion_rate' => $frequencyCompletionRate,
            ],

            'current_cycle' => $currentCycle,
        ];
    }

    /**
     * @return void
     */
    protected function buildPerformance(
    ): void {
        $completedCycles = $this->cycles
            ->where('status', Statuses::COMPLETED->value)
            ->sortByDesc('completed_at')
            ->values();

        $durations = $completedCycles->map(
            fn (AccountCycle $cycle) => $cycle->started_at->diffInDays($cycle->completed_at)
        );

        $amountRate = $this->statistics['amounts']['completion_rate'];
        $frequencyRate = $this->statistics['frequencies']['completion_rate'];

        $this->performance = [
            'discipline_score' => round(($amountRate + $frequencyRate) / 2),
            'average_cycle_duration_days' => $durations->isNotEmpty() ? round($durations->avg()) : null,
        ];
    }

    /**
     * @return void
     */
    protected function buildInsights(
    ): void {
        $completedCycles = $this->cycles->where('status', Statuses::COMPLETED->value);
        $hasActiveCycle = (bool) $this->statistics['current_cycle'];

        // Historical discipline (completed cycles only)
        if ($completedCycles->count() >= 3 && $this->performance['discipline_score'] >= 85) {
            $this->insights = [
                'scope' => 'historical',
                'type' => 'positive',
                'message' => 'You have consistently completed your savings cycles.',
            ];

            $this->recommendations = ['You may consider increasing your savings amount in your next cycle.'];
        }

        // Current cycle pacing
        if ($hasActiveCycle) {
            $currentCycle = $this->statistics['current_cycle'];

            if ($currentCycle->completed_frequencies === 1) {
                $this->insights = [
                    'scope' => 'current_cycle',
                    'type' => 'info',
                    'message' => 'Your new cycle has just started. Stay consistent to maintain your streak.',
                ];
            }

            if ($this->statistics['frequencies']['completion_rate'] < 70 && $currentCycle->completed_frequencies > 0) {
                $this->insights = [
                    'scope' => 'current_cycle',
                    'type' => 'warning',
                    'message' => 'Your current cycle is progressing slower than expected.',
                ];
            }
        }

        // Near completion
        if ($hasActiveCycle && $this->statistics['frequencies']['remaining'] === 0) {
            $this->insights = [
                'scope' => 'current_cycle',
                'type' => 'info',
                'message' => 'You are very close to completing your current cycle.',
            ];
        }
    }

    /**
     * @param Collection $items
     * @param callable $resolver
     * @param string $currency
     * @return Money
     */
    protected function sumMoney(
        Collection $items,
        callable $resolver,
        string $currency = 'GHS'
    ): Money {
        $total = null;

        foreach ($items as $item) {
            $money = $resolver($item);

            $total = $total
                ? $total->plus($money)
                : $money;
        }

        return $total ?? Money::zero($currency);
    }
}
