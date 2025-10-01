<?php

declare(strict_types=1);

use Domain\Shared\Enums\RecurringDebitStatus;
use Domain\Shared\Enums\WithdrawalStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'biz_susus',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')
                    ->unique()
                    ->index();

                // Table related fields
                $table->unsignedBigInteger(column: 'account_id');

                // Table main attributes
                $table->string(column: 'currency')
                    ->default(value: 'GHS');

                $table->boolean(column: 'rollover_enabled')
                    ->default(value: false);

                $table->boolean(column: 'is_collateralized')
                    ->default(value: false);

                $table->enum(column: 'recurring_debit_status', allowed: [
                    RecurringDebitStatus::ACTIVE->value,
                    RecurringDebitStatus::PENDING->value,
                    RecurringDebitStatus::PAUSED->value,
                    RecurringDebitStatus::STOPPED->value,
                ])
                    ->default(value: RecurringDebitStatus::PENDING->value);

                $table->string(column: 'withdrawal_status')
                    ->default(value: WithdrawalStatus::ACTIVE->value);

                $table->json(column: 'extra_data')
                    ->nullable();

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
            table: 'biz_susus'
        );
    }
};
