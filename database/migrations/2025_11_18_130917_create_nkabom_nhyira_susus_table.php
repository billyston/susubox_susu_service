<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'nkabom_nhyira_susus',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'group_account_id')->index()->constrained(table: 'group_accounts');
                $table->foreignId(column: 'frequency_id')->constrained(table: 'frequencies');
                $table->foreignId(column: 'cycle_duration_id')->constrained(table: 'durations');

                // Table main attributes
                $table->bigInteger(column: 'susu_amount');
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->integer(column: 'member_min_slot')->default(value: 1);
                $table->integer(column: 'member_max_slot')->default(value: 5);
                $table->integer(column: 'filled_slots')->default(value: 0);
                $table->date(column:'start_date')->default(value: Carbon::today());
                $table->date(column:'expected_end_date')->nullable();
                $table->date(column:'actual_end_date')->nullable();
                $table->json(column: 'extra_data')->nullable();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'nkabom_nhyira_susus'
        );
    }
};
