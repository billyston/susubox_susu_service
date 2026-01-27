<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\IndividualSusu\DailySusu\Statistics;

use App\Domain\Susu\Services\IndividualSusu\DailySusu\Statistics\DailySusuCycleStatisticsService;

final readonly class DailySusuStatisticsResponseDTO
{
    /**
     * @param DailySusuCycleStatisticsService $statistics
     * @param array $request
     */
    public function __construct(
        public DailySusuCycleStatisticsService $statistics,
        public array $request,
    ) {
        // ..
    }

    /**
     * @param DailySusuCycleStatisticsService $statistics
     * @param array $request
     * @return self
     */
    public static function fromDomain(
        DailySusuCycleStatisticsService $statistics,
        array $request,
    ): self {
        return new self(
            statistics: $statistics,
            request: $request,
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        $stats = $this->statistics->getStatistics();
        $period = $this->statistics->getPeriod();
        $performance = $this->statistics->getPerformance();

        $currentCycle = $stats['current_cycle'];

        return [
            'data' => [
                'type' => 'CycleStatistics',
                'attributes' => [
                    'total_cycles' => $stats['cycles']['total'],
                    'active_cycles' => $stats['cycles']['active'],
                    'completed_cycles' => $stats['cycles']['completed'],
                    'settled_cycles' => $stats['cycles']['settled'],
                    'rolled_over_cycles' => $stats['cycles']['rolled_over'],
                    'discipline_score' => $performance['discipline_score'],
                    'average_cycle_duration_days' => $performance['average_cycle_duration_days'],
                ],

                'included' => [
                    'period' => [
                        'type' => 'Period',
                        'attributes' => [
                            'date_from' => $period['from'],
                            'date_to' => $period['to'],
                        ],
                    ],
                    'amounts' => [
                        'type' => 'CycleContributionSummary',
                        'attributes' => [
                            'expected_amount' => $stats['amounts']['expected']->getAmount()->__toString(),
                            'contributed_amount' => $stats['amounts']['contributed']->getAmount()->__toString(),
                            'outstanding_amount' => $stats['amounts']['outstanding']->getAmount()->__toString(),
                            'completion_rate' => $stats['amounts']['completion_rate'],
                        ],
                    ],
                    'frequencies' => [
                        'type' => 'CycleFrequencySummary',
                        'attributes' => $stats['frequencies'],
                    ],
                    'current_cycle' => $currentCycle ? [
                        'type' => 'CurrentCycle',
                        'attributes' => [
                            'cycle_number' => $currentCycle->cycle_number,
                            'status' => $currentCycle->status,
                            'started_at' => $currentCycle->started_at->toDateString(),
                            'expected_frequencies' => $currentCycle->expected_frequencies,
                            'completed_frequencies' => $currentCycle->completed_frequencies,
                            'remaining_frequencies' => $currentCycle->remainingFrequencies(),
                            'completion_rate' => round($currentCycle->completed_frequencies / $currentCycle->expected_frequencies * 100, 2),
                        ],
                    ] : null,
                    'insights' => [
                        'type' => 'CycleInsights',
                        'attributes' => $this->statistics->getInsights()[0],
                    ],
                    'recommendations' => [
                        'type' => 'CycleRecommendations',
                        'attributes' => [
                            'recommendations' => $this->statistics->getRecommendations()[0],
                        ],
                    ],
                ],
            ],
        ];
    }
}
