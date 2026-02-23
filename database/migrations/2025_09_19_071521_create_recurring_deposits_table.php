<?php

declare(strict_types=1);

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
            table: 'recurring_deposits',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'account_id')->constrained(table: 'accounts')->cascadeOnDelete();
                $table->foreignId(column: 'account_customer_id')->constrained(table: 'account_customers')->cascadeOnDelete();
                $table->foreignId(column: 'payment_instruction_id')->unique()->constrained(table: 'payment_instructions')->cascadeOnDelete();
                $table->foreignId(column: 'frequency_id')->constrained(table: 'frequencies')->restrictOnDelete();

                // Table main attributes
                $table->bigInteger(column: 'recurring_amount');
                $table->bigInteger(column: 'initial_amount');
                $table->integer(column: 'initial_frequency')->nullable();
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->boolean(column: 'rollover_enabled')->default(value: false);
                $table->unsignedInteger(column: 'rollover_count')->default(value: 0);
                $table->enum(column: 'status', allowed: [
                    Statuses::ACTIVE->value,
                    Statuses::PENDING->value,
                    Statuses::PAUSED->value,
                    Statuses::STOPPED->value,
                ])->default(value: Statuses::PENDING->value);
                $table->json(column: 'metadata')->nullable();

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
            table: 'recurring_deposits'
        );
    }
};
