<?php

declare(strict_types=1);

namespace App\Application\Transaction\Services\Statistics;

use App\Domain\Account\Models\Account;
use Carbon\CarbonInterface;

final class PerformanceReportGenerator
{
    /**
     * @param Account $account
     * @param array $statistics
     * @param CarbonInterface $fromDate
     * @param CarbonInterface $toDate
     * @return array
     */
    public function generate(
        Account $account,
        array $statistics,
        CarbonInterface $fromDate,
        CarbonInterface $toDate
    ): array {
        return [
            'account' => [
                'id' => $account->id,
                'account_number' => $account->account_number,
                'name' => $account->account_name ?? 'N/A',
            ],
            'period' => [
                'from' => $fromDate->toDateString(),
                'to' => $toDate->toDateString(),
                'days' => $fromDate->diffInDays($toDate) + 1,
            ],
            'summary' => $this->generateSummary($statistics),
            'key_metrics' => $this->generateKeyMetrics($statistics),
            'insights' => $this->generateInsights($statistics),
            'recommendations' => $this->generateRecommendations($statistics),
            'generated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * @param array $statistics
     * @return float
     */
    public function calculatePerformanceScore(
        array $statistics
    ): float {
        $score = 0;

        $successScore = min($statistics['basic']['success_rate'] * 0.3, 30);
        $score += $successScore;

        $feeEfficiency = max(0, 100 - ($statistics['fees']['charge_to_amount_ratio'] * 20));
        $score += min($feeEfficiency * 0.25, 25);

        $growthScore = min(max($statistics['trends']['volume']['change'], 0) * 0.2, 20);
        $score += $growthScore;

        $score += $statistics['time_based']['consistency_score'] * 0.15;

        $categoryConcentration = collect($statistics['categories'])
            ->pluck('percentage_of_volume')
            ->max();
        $diversityScore = max(0, 100 - ($categoryConcentration * 0.5));
        $score += min($diversityScore * 0.1, 10);

        return round($score, 2);
    }

    /**
     * @param array $statistics
     * @return string
     */
    public function getPerformanceGrade(
        array $statistics
    ): string {
        $score = $this->calculatePerformanceScore($statistics);

        return match (true) {
            $score >= 90 => 'A+',
            $score >= 85 => 'A',
            $score >= 80 => 'A-',
            $score >= 75 => 'B+',
            $score >= 70 => 'B',
            $score >= 65 => 'B-',
            $score >= 60 => 'C+',
            $score >= 50 => 'C',

            default => 'D',
        };
    }

    /**
     * @param array $statistics
     * @return array[]
     */
    private function generateSummary(
        array $statistics
    ): array {
        return [
            'overall_performance' => [
                'score' => $this->calculatePerformanceScore($statistics),
                'grade' => $this->getPerformanceGrade($statistics),
                'status' => $this->getPerformanceStatus($statistics),
            ],
            'financial_health' => [
                'net_growth' => $statistics['financial']['net_flow'],
                'liquidity_ratio' => $this->calculateLiquidityRatio($statistics),
                'efficiency_score' => $this->calculateEfficiencyScore($statistics),
            ],
            'operational_efficiency' => [
                'success_rate' => $statistics['basic']['success_rate'],
                'cost_efficiency' => round(100 - min($statistics['fees']['charge_to_amount_ratio'], 100), 2),
            ],
        ];
    }

    /**
     * @param array $statistics
     * @return array[]
     */
    private function generateKeyMetrics(
        array $statistics
    ): array {
        return [
            'transaction_volume' => [
                'value' => $statistics['financial']['total_volume'],
                'trend' => $statistics['trends']['volume']['direction'],
                'change' => $statistics['trends']['volume']['change'],
            ],
            'success_rate' => [
                'value' => $statistics['basic']['success_rate'],
                'target' => 98.0,
                'status' => $statistics['basic']['success_rate'] >= 98 ? 'excellent' :
                    ($statistics['basic']['success_rate'] >= 95 ? 'good' : 'needs_improvement'),
            ],
            'average_transaction_value' => [
                'value' => $statistics['financial']['credits']['average'],
                'trend' => $statistics['trends']['average_value']['change'] > 0 ? 'up' :
                    ($statistics['trends']['average_value']['change'] < 0 ? 'down' : 'stable'),
            ],
            'cost_of_transactions' => [
                'value' => $statistics['fees']['charge_to_amount_ratio'],
                'target' => 1.5,
                'status' => $statistics['fees']['charge_to_amount_ratio'] <= 1.5 ? 'good' : 'high',
            ],
        ];
    }

    /**
     * @param array $statistics
     * @return array
     */
    private function generateInsights(
        array $statistics
    ): array {
        $insights = [];

        if ($statistics['basic']['success_rate'] < 95) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Transaction Success Rate Below Target',
                'description' => 'Current success rate is ' . $statistics['basic']['success_rate'] . '%, below the target of 95%.',
                'impact' => 'Medium',
                'suggestion' => 'Review failed transactions and implement corrective measures.',
            ];
        }

        if ($statistics['fees']['charge_to_amount_ratio'] > 2) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'High Transaction Costs',
                'description' => 'Transaction costs are ' . $statistics['fees']['charge_to_amount_ratio'] . '% of total volume.',
                'impact' => 'High',
                'suggestion' => 'Consider optimizing fee structure or transaction bundling.',
            ];
        }

        if ($statistics['trends']['volume']['change'] > 20) {
            $insights[] = [
                'type' => 'positive',
                'title' => 'Strong Volume Growth',
                'description' => 'Transaction volume increased by ' . $statistics['trends']['volume']['change'] . '% compared to previous period.',
                'impact' => 'High',
                'suggestion' => 'Consider expanding capacity to maintain service quality.',
            ];
        }

        return $insights;
    }

    /**
     * @param array $statistics
     * @return array
     */
    private function generateRecommendations(
        array $statistics
    ): array {
        $recommendations = [];

        if ($statistics['basic']['success_rate'] < 95) {
            $failedCategories = collect($statistics['categories'])
                ->where('success_rate', '<', 90)
                ->sortBy('success_rate');

            if ($failedCategories->isNotEmpty()) {
                $worstCategory = $failedCategories->first();
                $recommendations[] = [
                    'priority' => 'high',
                    'action' => "Improve success rate for {$worstCategory['name']} transactions",
                    'reason' => "Current success rate: {$worstCategory['success_rate']}%",
                    'estimated_impact' => 'High',
                ];
            }
        }

        if ($statistics['fees']['charge_to_amount_ratio'] > 2) {
            $recommendations[] = [
                'priority' => 'medium',
                'action' => 'Optimize transaction fee structure',
                'reason' => 'High cost-to-volume ratio',
                'estimated_impact' => 'Medium',
            ];
        }

        return $recommendations;
    }

    /**
     * @param array $statistics
     * @return string
     */
    private function getPerformanceStatus(
        array $statistics
    ): string {
        $score = $this->calculatePerformanceScore($statistics);

        return match (true) {
            $score >= 80 => 'Excellent',
            $score >= 70 => 'Good',
            $score >= 60 => 'Fair',

            default => 'Needs Improvement',
        };
    }

    /**
     * @param array $statistics
     * @return float
     */
    private function calculateLiquidityRatio(
        array $statistics
    ): float {
        $debitsValue = $statistics['financial']['debits']['total_money']->getAmount()->toFloat();
        $creditsValue = $statistics['financial']['credits']['total_money']->getAmount()->toFloat();

        if ($creditsValue > 0) {
            return round($debitsValue / $creditsValue, 2);
        }

        return 0;
    }

    /**
     * @param array $statistics
     * @return float
     */
    private function calculateEfficiencyScore(
        array $statistics
    ): float {
        $successRateScore = $statistics['basic']['success_rate'];
        $feeEfficiencyScore = 100 - min($statistics['fees']['charge_to_amount_ratio'] * 10, 100);
        $consistencyScore = $statistics['time_based']['consistency_score'];

        return round(($successRateScore * 0.4 + $feeEfficiencyScore * 0.3 + $consistencyScore * 0.3), 2);
    }
}
