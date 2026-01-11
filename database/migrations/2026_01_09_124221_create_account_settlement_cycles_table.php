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
            table: 'account_settlement_cycles',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related fields
                $table->foreignId(column: 'account_settlement_id')->constrained(table: 'account_settlements')->cascadeOnDelete();
                $table->foreignId(column: 'account_cycle_id')->constrained(table: 'account_cycles')->restrictOnDelete();

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();

                $table->unique(['account_settlement_id', 'account_cycle_id'], name: 'settlement_cycle_unique');
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_settlement_cycles'
        );
    }
};
