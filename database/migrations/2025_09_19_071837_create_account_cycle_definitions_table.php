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
            table: 'account_cycle_definitions',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'account_id')->unique()->constrained(table: 'accounts')->cascadeOnDelete();

                // Table main attributes
                $table->unsignedSmallInteger(column: 'cycle_length')->default(value: 31);
                $table->unsignedSmallInteger(column: 'commission_frequencies')->default(value: 1);
                $table->unsignedSmallInteger(column: 'payout_frequencies')->default(value: 30);
                $table->unsignedSmallInteger(column: 'expected_frequencies')->default(value: 31);
                $table->bigInteger(column: 'expected_cycle_amount');
                $table->bigInteger(column: 'expected_payout_amount');
                $table->bigInteger(column: 'commission_amount');
                $table->string(column: 'currency')->default(value: 'GHS');

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
            table: 'account_cycle_definitions'
        );
    }
};
