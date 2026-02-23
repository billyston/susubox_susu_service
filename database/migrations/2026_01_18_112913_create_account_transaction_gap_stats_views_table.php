<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(
    ): void {
        DB::statement(query: "
            CREATE OR REPLACE VIEW account_transaction_gap_stats_view AS
            SELECT
                account_id,

                MAX(gap_days) AS longest_transaction_gap_days,

                MAX(gap_days) FILTER (WHERE transaction_type = 'credit') AS longest_credit_gap_days,
                MAX(gap_days) FILTER (WHERE transaction_type = 'debit') AS longest_debit_gap_days

            FROM (
                SELECT
                    account_id,
                    transaction_type,
                    EXTRACT(
                        DAY FROM (
                            created_at -
                            LAG(created_at)
                            OVER (
                                PARTITION BY account_id, transaction_type
                                ORDER BY created_at
                            )
                        )
                    ) AS gap_days
                FROM transactions
            ) t
            WHERE gap_days IS NOT NULL
            GROUP BY account_id;
        ");
    }

    /**
     * @return void
     */
    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_transaction_gap_stats_views'
        );
    }
};
