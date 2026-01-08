<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Statuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
                $table->foreignId(column: 'account_id')->constrained(table: 'accounts')->cascadeOnDelete();

                $table->string(column: 'cycleable_type')->index();
                $table->unsignedBigInteger(column: 'cycleable_id')->index();

                // Table main attributes
                $table->unsignedInteger(column: 'cycle_number');
                $table->unsignedInteger(column: 'expected_frequencies');
                $table->unsignedInteger(column: 'completed_frequencies')->default(value: 0);

                $table->bigInteger(column: 'expected_amount');
                $table->bigInteger(column: 'contributed_amount')->default(value: 0);
                $table->string(column: 'currency')->default(value: 'GHS');

                $table->timestamp(column: 'started_at');
                $table->timestamp(column: 'completed_at')->nullable();
                $table->timestamp(column: 'settled_at')->nullable();

                $table->json(column: 'extra_data')->nullable();

                $table->enum(column: 'status', allowed: [
                    Statuses::ACTIVE->value,
                    Statuses::COMPLETED->value,
                    Statuses::SETTLED->value,
                    Statuses::ROLLED_OVER->value,
                ])->index();

                $table->unique([
                    'account_id',
                    'cycle_number',
                ]);

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_cycles'
        );
    }
};
