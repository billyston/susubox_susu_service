<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'nkabom_nhyira_susu_payout_queues',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related fields
                $table->foreignId(column: 'nkabom_nhyira_susu_id')->constrained(table: 'nkabom_nhyira_susus');
                $table->foreignId(column:'nkabom_nhyira_susu_member_id')->constrained(table: 'nkabom_nhyira_susu_members');

                // Table main attributes
                $table->integer(column: 'queue_position');
                $table->timestamp(column:'scheduled_payout_date')->nullable();
                $table->timestamp(column:'actual_payout_date')->nullable();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'nkabom_nhyira_susu_payout_queues'
        );
    }
};
