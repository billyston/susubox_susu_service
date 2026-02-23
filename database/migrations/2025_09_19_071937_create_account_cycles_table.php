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
            table: 'account_cycles',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'account_id')->unique()->constrained(table: 'accounts')->cascadeOnDelete();
                $table->foreignId(column: 'account_cycle_definition_id')->constrained(table: 'account_cycle_definitions')->restrictOnDelete();

                // Table main attributes
                $table->unsignedInteger(column: 'cycle_number')->unique();
                $table->unsignedInteger(column: 'expected_frequencies');
                $table->unsignedInteger(column: 'completed_frequencies')->default(value: 0);
                $table->bigInteger(column: 'expected_amount');
                $table->bigInteger(column: 'contributed_amount')->default(value: 0);
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->timestamp(column: 'started_at');
                $table->timestamp(column: 'completed_at')->nullable();
                $table->timestamp(column: 'settled_at')->nullable();
                $table->enum(column: 'status', allowed: [
                    Statuses::ACTIVE,
                    Statuses::COMPLETED,
                    Statuses::SETTLED,
                    Statuses::ROLLED_OVER,
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
            table: 'account_cycles'
        );
    }
};
