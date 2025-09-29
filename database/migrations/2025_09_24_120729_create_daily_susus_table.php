<?php

declare(strict_types=1);

use Carbon\Carbon;
use Domain\Shared\Enums\RecurringDebitStatus;
use Domain\Susu\Enums\DailySusu\DailySusuSettlementStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'daily_susus',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related fields
                $table->unsignedBigInteger(column: 'account_id');

                // Table main attributes
                $table->string(column: 'currency')
                    ->default(value: 'GHS');

                $table->boolean(column: 'rollover_enabled')
                    ->default(value: false);

                $table->boolean(column: 'is_collateralized')
                    ->default(value: false);

                $table->boolean(column: 'auto_settlement')
                    ->default(value: false);

                $table->enum(column: 'recurring_debit_status', allowed: [
                    RecurringDebitStatus::ACTIVE->value,
                    RecurringDebitStatus::PENDING->value,
                    RecurringDebitStatus::PAUSED->value,
                    RecurringDebitStatus::STOPPED->value,
                ])
                    ->default(value: RecurringDebitStatus::PENDING->value);

                $table->enum(column: 'settlement_status', allowed: [
                    DailySusuSettlementStatus::ACTIVE->value,
                    DailySusuSettlementStatus::LOCKED->value,
                ])
                    ->default(value: DailySusuSettlementStatus::ACTIVE->value);

                $table->json(column: 'extra_data')->nullable();

                // Foreign key fields
                $table->foreign(columns: 'account_id')
                    ->references(columns: 'id')
                    ->on(table: 'accounts')
                    ->onDelete(action: 'cascade');

                // Timestamps (created_at / updated_at) fields
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'daily_susus'
        );
    }
};
