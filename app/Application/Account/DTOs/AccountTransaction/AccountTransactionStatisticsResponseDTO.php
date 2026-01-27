<?php

declare(strict_types=1);

namespace App\Application\Account\DTOs\AccountTransaction;

use App\Application\Transaction\Services\Statistics\AccountTransactionStatsService;
use App\Domain\Transaction\Enums\TransactionCategoryCode;

final readonly class AccountTransactionStatisticsResponseDTO
{
    public function __construct(
        public AccountTransactionStatsService $statistics,
        public array $request
    ) {
        // ..
    }

    public static function fromDomain(
        AccountTransactionStatsService $statistics,
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
        // Get the main data
        $period = $this->statistics->getPeriod();

        // Get the transaction stats
        $basic = $this->statistics->getStatistics()['basic'];
        $financial = $this->statistics->getStatistics()['financial'];
        $creditTransaction = $this->statistics->getStatistics()['financial']['credits'];
        $debitTransaction = $this->statistics->getStatistics()['financial']['debits'];

        // Get the transaction categories
        $recurringTransaction = $this->statistics->getStatistics()['categories'][TransactionCategoryCode::RECURRING_DEBIT_CODE->value];
        $directTransaction = $this->statistics->getStatistics()['categories'][TransactionCategoryCode::DIRECT_DEBIT_CODE->value];
        $settlementTransaction = $this->statistics->getStatistics()['categories'][TransactionCategoryCode::SETTLEMENT_CODE->value];
        $withdrawalTransaction = $this->statistics->getStatistics()['categories'][TransactionCategoryCode::WITHDRAWAL_CODE->value];
        $transactionTimeBase = $this->statistics->getStatistics()['time_based'];

        // Get the transaction time bases
        $performanceReport = $this->statistics->getPerformanceReport();

        return [
            'data' => [
                'type' => 'AccountTransactionStatistics',
                'attributes' => [
                    'date_from' => $period['from'],
                    'date_to' => $period['to'],
                    'total_transaction' => $basic['total'],
                    'total_successful' => $basic['successful'],
                    'total_failed' => $basic['failed'],
                    'total_reversed' => $basic['reversed'],
                    'total_refunded' => $basic['refunded'],
                    'total_cancelled' => $basic['cancelled'],
                    'net_flow_amount' => $financial['net_flow'],
                    'total_amount' => $financial['total_volume'],
                    'turnover_ratio' => $financial['turnover_ratio'],
                    'success_rate' => $basic['success_rate'],
                ],

                'included' => [
                    'credit_transaction' => [
                        'type' => 'CreditTransaction',
                        'attributes' => [
                            'total_transaction' => $creditTransaction['count'],
                            'total_amount' => $creditTransaction['total'],
                            'average_amount' => $creditTransaction['average'],
                            'min_amount' => $creditTransaction['min'],
                            'max_amount' => $creditTransaction['max'],
                            'median_amount' => $creditTransaction['median'],
                        ],
                    ],
                    'debit_transaction' => [
                        'type' => 'DebitTransaction',
                        'attributes' => [
                            'total_transaction' => $debitTransaction['count'],
                            'total_amount' => $debitTransaction['total'],
                            'average_amount' => $debitTransaction['average'],
                            'min_amount' => $debitTransaction['min'],
                            'max_amount' => $debitTransaction['max'],
                            'median_amount' => $debitTransaction['median'],
                        ],
                    ],
                    'recurring_deposit_transaction' => [
                        'type' => 'RecurringDepositTransaction',
                        'attributes' => [
                            'total_transaction' => $recurringTransaction['count'],
                            'total_successful' => $recurringTransaction['successful'],
                            'total_failed' => $recurringTransaction['failed'],
                            'total_amount' => $recurringTransaction['total_amount'],
                            'total_credit_amount' => $recurringTransaction['breakdown']['credits'],
                            'total_debit_amount' => $recurringTransaction['breakdown']['debits'],
                            'average_amount' => $recurringTransaction['average_amount'],
                            'success_rate' => $recurringTransaction['success_rate'],
                            'percentage_of_total' => $recurringTransaction['percentage_of_total'],
                            'percentage_of_volume' => $recurringTransaction['percentage_of_volume'],
                        ],
                    ],
                    'direct_deposit_transaction' => [
                        'type' => 'DirectDepositTransaction',
                        'attributes' => [
                            'total_transaction' => $directTransaction['count'],
                            'total_successful' => $directTransaction['successful'],
                            'total_failed' => $directTransaction['failed'],
                            'total_amount' => $directTransaction['total_amount'],
                            'total_credit_amount' => $directTransaction['breakdown']['credits'],
                            'total_debit_amount' => $directTransaction['breakdown']['debits'],
                            'average_amount' => $directTransaction['average_amount'],
                            'success_rate' => $directTransaction['success_rate'],
                            'percentage_of_total' => $directTransaction['percentage_of_total'],
                            'percentage_of_volume' => $directTransaction['percentage_of_volume'],
                        ],
                    ],
                    'settlement_transaction' => [
                        'type' => 'SettlementTransaction',
                        'attributes' => [
                            'total_transaction' => $settlementTransaction['count'],
                            'total_successful' => $settlementTransaction['successful'],
                            'total_failed' => $settlementTransaction['failed'],
                            'total_amount' => $settlementTransaction['total_amount'],
                            'total_credit_amount' => $settlementTransaction['breakdown']['credits'],
                            'total_debit_amount' => $settlementTransaction['breakdown']['debits'],
                            'average_amount' => $settlementTransaction['average_amount'],
                            'success_rate' => $settlementTransaction['success_rate'],
                            'percentage_of_total' => $settlementTransaction['percentage_of_total'],
                            'percentage_of_volume' => $settlementTransaction['percentage_of_volume'],
                        ],
                    ],
                    'withdrawal_transaction' => [
                        'type' => 'WithdrawalTransaction',
                        'attributes' => [
                            'total_transaction' => $withdrawalTransaction['count'],
                            'total_successful' => $withdrawalTransaction['successful'],
                            'total_failed' => $withdrawalTransaction['failed'],
                            'total_amount' => $withdrawalTransaction['total_amount'],
                            'total_credit_amount' => $withdrawalTransaction['breakdown']['credits'],
                            'total_debit_amount' => $withdrawalTransaction['breakdown']['debits'],
                            'average_amount' => $withdrawalTransaction['average_amount'],
                            'success_rate' => $withdrawalTransaction['success_rate'],
                            'percentage_of_total' => $withdrawalTransaction['percentage_of_total'],
                            'percentage_of_volume' => $withdrawalTransaction['percentage_of_volume'],
                        ],
                    ],
                    'transaction_time_base' => $this->buildTransactionTimeBase($transactionTimeBase),
                    'transaction_time_base_summary' => [
                        'type' => 'TransactionTimeBaseSummary',
                        'attributes' => [
                            'consistency_score' => $transactionTimeBase['consistency_score'],
                            'days_active' => $transactionTimeBase['days_active'],
                            'avg_daily_transactions' => $transactionTimeBase['avg_daily_transactions'],
                            'avg_daily_volume' => $transactionTimeBase['avg_daily_volume'],
                        ],
                    ],
                    'dashboard_metrics' => [
                        'type' => 'DashBoardMetrics',
                        'attributes' => $this->statistics->getDashboardMetrics(),
                    ],
                    'performance_report' => [
                        'type' => 'PerformanceReport',
                        'attributes' => [
                            'performance_score' => $performanceReport['summary']['overall_performance']['score'],
                            'performance_grade' => $performanceReport['summary']['overall_performance']['grade'],
                            'performance_status' => $performanceReport['summary']['overall_performance']['status'],
                            'financial_net_growth' => $performanceReport['summary']['financial_health']['net_growth'],
                            'financial_liquidity_ratio' => $performanceReport['summary']['financial_health']['liquidity_ratio'],
                            'financial_efficiency_score' => $performanceReport['summary']['financial_health']['efficiency_score'],
                            'efficiency_success_rate' => $performanceReport['summary']['operational_efficiency']['success_rate'],
                            'cost_efficiency' => $performanceReport['summary']['operational_efficiency']['cost_efficiency'],
                            'insight_type' => $performanceReport['insights'][0]['type'],
                            'insight_title' => $performanceReport['insights'][0]['title'],
                            'insight_description' => $performanceReport['insights'][0]['description'],
                            'insight_impact' => $performanceReport['insights'][0]['impact'],
                            'insight_suggestion' => $performanceReport['insights'][0]['suggestion'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $transactionTimeBase
     * @return array
     */
    protected function buildTransactionTimeBase(
        array $transactionTimeBase
    ): array {
        $records = [];

        // Daily
        foreach ($transactionTimeBase['daily'] ?? [] as $item) {
            $records[] = [
                'type' => 'DailyTransactionTimeBase',
                'attributes' => [
                    'date' => $item['date'],
                    'total_transactions' => $item['count'],
                    'average_money' => $item['average_money']->getAmount()->__toString(),
                    'total_credits_money' => $item['credits_money']->getAmount()->__toString(),
                    'total_debits_money' => $item['debits_money']->getAmount()->__toString(),
                ],
            ];
        }

        // Weekly
        foreach ($transactionTimeBase['weekly'] ?? [] as $item) {
            $records[] = [
                'type' => 'WeeklyTransactionTimeBase',
                'attributes' => [
                    'week' => $item['week'],
                    'total_transactions' => $item['count'],
                    'average_money' => $item['average_money']->getAmount()->__toString(),
                    'total_money' => $item['total_money']->getAmount()->__toString(),
                ],
            ];
        }

        // Monthly
        foreach ($transactionTimeBase['monthly'] ?? [] as $item) {
            $records[] = [
                'type' => 'MonthlyTransactionTimeBase',
                'attributes' => [
                    'month' => $item['month'],
                    'total_transactions' => $item['count'],
                    'average_money' => $item['average_money']->getAmount()->__toString(),
                    'total_money' => $item['total_money']->getAmount()->__toString(),
                ],
            ];
        }

        // Hourly
        foreach ($transactionTimeBase['hourly'] ?? [] as $item) {
            $records[] = [
                'type' => 'HourlyTransactionTimeBase',
                'attributes' => [
                    'hour' => $item['hour'],
                    'total_transactions' => $item['count'],
                    'total_money' => $item['total_money']->getAmount()->__toString(),
                ],
            ];
        }

        return $records;
    }
}
