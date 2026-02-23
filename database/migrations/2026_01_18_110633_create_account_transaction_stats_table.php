<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(
    ): void {
        DB::statement(query: "
            CREATE OR REPLACE VIEW account_transaction_stats_view AS
            SELECT
                account_id,

                COUNT(*) AS total_transactions,

                COUNT(*) FILTER (WHERE transaction_type = 'credit') AS credit_transaction_count,
                COUNT(*) FILTER (WHERE transaction_type = 'debit') AS debit_transaction_count,

                COUNT(*) FILTER (WHERE status = 'success') AS successful_transactions,
                COUNT(*) FILTER (WHERE status = 'failed') AS failed_transactions,
                COUNT(*) FILTER (WHERE status = 'reversed') AS reversed_transactions,

                COALESCE(SUM(total) FILTER (WHERE transaction_type = 'credit'), 0) AS total_credit_amount,
                COALESCE(SUM(total) FILTER (WHERE transaction_type = 'debit'), 0) AS total_debit_amount,

                COALESCE(SUM(total) FILTER (WHERE transaction_type = 'credit'), 0) -
                COALESCE(SUM(total) FILTER (WHERE transaction_type = 'debit'), 0) AS net_transaction_balance,
                MAX(currency) AS currency,

                MIN(created_at) AS first_transaction_date,
                MAX(created_at) AS last_transaction_date,

                MAX(created_at) FILTER (WHERE status = 'success') AS last_successful_transaction_date,
                MAX(created_at) FILTER (WHERE status = 'failed') AS last_failed_transaction_date

            FROM transactions
            GROUP BY account_id;
        ");
    }

    /**
     * @return void
     */
    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_transaction_stats_view'
        );
    }
};
