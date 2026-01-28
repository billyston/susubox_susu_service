<?php

declare(strict_types=1);

namespace App\Application\Susu\DTOs\IndividualSusu\DailySusu\Statistics;

use App\Domain\Susu\Services\IndividualSusu\DailySusu\Statistics\DailySusuSettlementStatisticsService;

final readonly class DailySusuSettlementStatisticsResponseDTO
{
    /**
     * @param DailySusuSettlementStatisticsService $statistics
     * @param array $request
     */
    public function __construct(
        public DailySusuSettlementStatisticsService $statistics,
        public array $request,
    ) {
        // ..
    }

    /**
     * @param DailySusuSettlementStatisticsService $statistics
     * @param array $request
     * @return self
     */
    public static function fromDomain(
        DailySusuSettlementStatisticsService $statistics,
        array $request = [],
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
        $period = $this->statistics->getPeriod();
        $stats = $this->statistics->getStatistics();
        $performance = $this->statistics->getPerformance();

        return [
            'data' => [
                'type' => 'SettlementStatistics',
                'attributes' => [
                    'total_settlements' => $stats['counts']['total'],
                    'completed_settlements' => $stats['counts']['completed'],
                    'pending_settlements' => $stats['counts']['pending'],
                    'failed_settlements' => $stats['counts']['failed'],
                    'cancelled_settlements' => $stats['counts']['cancelled'],
                    'success_rate' => $performance['success_rate'],
                    'average_settlement_amount' => $performance['average_settlement_amount']->getAmount()->__toString(),
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
                        'type' => 'Amount',
                        'attributes' => [
                            'principal_total' => $stats['amounts']['principal_total']->getAmount()->__toString(),
                            'charges_total' => $stats['amounts']['charges_total']->getAmount()->__toString(),
                            'total_paid' => $stats['amounts']['total_paid']->getAmount()->__toString(),
                        ],
                    ],
                    'insights' => [
                        'type' => 'Insights',
                        'attributes' => $this->statistics->getInsights(),
                    ],
                    'recommendations' => [
                        'type' => 'Recommendations',
                        'attributes' => $this->statistics->getRecommendations(),
                    ],

                    'last_completed_settlement' => $stats['last_completed']
                        ? [
                            'type' => 'LastCompletedSettlement',
                            'attributes' => [
                                'resource_id' => $stats['last_completed']->resource_id,
                                'amount' => $stats['last_completed']->total_amount->getAmount()->__toString(),
                                'completed_at' => $stats['last_completed']->completed_at?->toDateTimeString(),
                            ],
                        ]
                        : null,
                ],
            ],
        ];
    }
}
