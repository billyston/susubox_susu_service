<?php

declare(strict_types=1);

use App\Application\Shared\Helpers\Helpers;
use App\Domain\Shared\Enums\Statuses;
use Carbon\Carbon;
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
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'individual_account_id')->constrained(table: 'individual_accounts')->cascadeOnDelete();
                $table->foreignId(column: 'wallet_id')->constrained(table: 'wallets');
                $table->foreignId(column: 'frequency_id')->constrained(table: 'frequencies');

                // Table main attributes
                $table->bigInteger(column: 'susu_amount');
                $table->bigInteger(column: 'initial_deposit');
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->date(column: 'start_date')->default(value: Carbon::today());
                $table->date(column: 'end_date')->default(value: Helpers::getEndCollectionDate());
                $table->boolean(column: 'rollover_enabled')->default(value: false);
                $table->boolean(column: 'is_collateralized')->default(value: false);
                $table->enum(column: 'recurring_debit_status', allowed: [
                    Statuses::ACTIVE->value,
                    Statuses::PENDING->value,
                    Statuses::PAUSED->value,
                    Statuses::STOPPED->value,
                ])->default(value: Statuses::PENDING->value);
                $table->enum(column: 'withdrawal_status', allowed: [
                    Statuses::ACTIVE->value,
                    Statuses::LOCKED->value,
                ])->default(value: Statuses::ACTIVE->value);
                $table->json(column: 'extra_data')->nullable();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'biz_susus'
        );
    }
};
