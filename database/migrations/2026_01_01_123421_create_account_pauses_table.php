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
            table: 'account_pauses',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Polymorphic relation
                $table->foreignId(column: 'payment_instruction_id')->constrained(table: 'payment_instructions');
                $table->morphs(name: 'pauseable');

                // Table main attributes
                $table->timestamp(column: 'paused_at')->default(value: Carbon::today());
                $table->timestamp(column: 'resumed_at')->nullable();

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
            table: 'account_pauses'
        );
    }
};
