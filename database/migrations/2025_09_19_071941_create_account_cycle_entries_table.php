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
            table: 'account_cycle_entries',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'account_customer_id')->constrained()->cascadeOnDelete();
                $table->foreignId(column: 'account_cycle_id')->constrained(table: 'account_cycles')->cascadeOnDelete();
                $table->foreignId(column: 'payment_instruction_id')->constrained(table: 'payment_instructions')->cascadeOnDelete();
                $table->foreignId(column: 'transaction_id')->unique()->constrained(table: 'transactions')->cascadeOnDelete();

                // Table main attributes
                $table->integer(column: 'frequencies');
                $table->bigInteger(column: 'amount');
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->enum(column: 'entry_type', allowed: [
                    'initial',
                    'recurring',
                    'direct',
                ])->index();
                $table->timestamp(column: 'posted_at');
                $table->enum(column: 'status', allowed: [
                    Statuses::SUCCESS->value,
                    Statuses::REVERSED->value,
                ])->index();

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
            table: 'account_cycle_entries'
        );
    }
};
