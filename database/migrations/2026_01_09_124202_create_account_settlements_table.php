<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Initiators;
use App\Domain\Shared\Enums\SettlementScopes;
use App\Domain\Shared\Enums\Statuses;
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
            table: 'account_settlements',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique();

                // Table related fields
                $table->foreignId(column: 'account_id')->constrained(table: 'accounts')->cascadeOnDelete();
                $table->foreignId(column: 'payment_instruction_id')->constrained(table: 'payment_instructions')->cascadeOnDelete();

                // Table main attributes
                $table->enum(column: 'initiated_by', allowed: [
                    Initiators::CUSTOMER->value,
                    Initiators::ADMINISTRATOR->value,
                    Initiators::SYSTEM->value,
                    Initiators::SCHEDULED->value,
                ])->default(value: Initiators::CUSTOMER->value);
                $table->enum(column: 'settlement_scope', allowed: [
                    SettlementScopes::SELECTED_COMPLETED->value,
                    SettlementScopes::ALL_COMPLETED->value,
                    SettlementScopes::ALL_INCLUDING_RUNNING->value,
                    SettlementScopes::AUTO_SETTLEMENT->value,
                ])->index();
                $table->bigInteger(column: 'principal_amount');
                $table->bigInteger(column: 'charge_amount');
                $table->bigInteger(column: 'total_amount');
                $table->string(column: 'currency')->default(value: 'GHS');

                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::PROCESSING->value,
                    Statuses::COMPLETED->value,
                    Statuses::CANCELLED->value,
                    Statuses::FAILED->value,
                ])->index();
                $table->timestamp(column: 'completed_at')->nullable();

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
            table: 'account_settlements'
        );
    }
};
