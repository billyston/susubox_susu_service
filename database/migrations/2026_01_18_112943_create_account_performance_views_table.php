<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        DB::statement(query: "
            CREATE OR REPLACE VIEW account_performance_view AS
            SELECT
                ats.*,

                gaps.longest_transaction_gap_days,
                gaps.longest_credit_gap_days,
                gaps.longest_debit_gap_days

            FROM account_transaction_stats_view ats
            LEFT JOIN account_transaction_gap_stats_view gaps
                ON gaps.account_id = ats.account_id;
        ");
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_performance_views'
        );
    }
};
