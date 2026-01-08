<?php

declare(strict_types=1);

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
            table: 'account_locks',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Polymorphic relation
                $table->morphs(name: 'lockable');

                // Table main attributes
                $table->timestamp(column: 'locked_at')->default(value: Carbon::today());
                $table->timestamp(column: 'unlocked_at')->nullable();

                $table->boolean(column: 'accepted_terms')->default(value: false);

                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::APPROVED->value,
                    Statuses::ACTIVE->value,
                    Statuses::CANCELLED->value,
                    Statuses::SUSPENDED->value,
                    Statuses::COMPLETED->value,
                ])->default(value: Statuses::PENDING->value);

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_locks'
        );
    }
};
