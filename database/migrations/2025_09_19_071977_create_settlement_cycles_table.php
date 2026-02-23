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
        Schema::create(
            table: 'settlement_cycles',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related fields
                $table->foreignId(column: 'settlement_id')->unique()->constrained(table: 'settlements')->cascadeOnDelete();
                $table->foreignId(column: 'account_cycle_id')->unique()->constrained(table: 'account_cycles')->restrictOnDelete();

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    /**
     * @return void
     */
    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'settlement_cycles'
        );
    }
};
