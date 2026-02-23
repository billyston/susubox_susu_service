<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Statuses;
use Carbon\Carbon;
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
            table: 'recurring_deposit_pauses',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Polymorphic relation
                $table->foreignId(column: 'recurring_deposit_id')->constrained(table: 'recurring_deposits')->cascadeOnDelete();

                // Table main attributes
                $table->timestamp(column: 'paused_at')->default(value: Carbon::today());
                $table->timestamp(column: 'expires_at')->nullable();
                $table->boolean(column: 'accepted_terms')->default(value: false);
                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::APPROVED->value,
                    Statuses::ACTIVE->value,
                    Statuses::CANCELLED->value,
                    Statuses::FAILED->value,
                    Statuses::SUSPENDED->value,
                    Statuses::COMPLETED->value,
                ])->default(value: Statuses::PENDING->value);

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
            table: 'recurring_deposit_pauses'
        );
    }
};
