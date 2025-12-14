<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Statuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'accounts',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Add polymorphic fields for accountable relationship
                $table->string(column: 'accountable_type')->nullable()->index();
                $table->unsignedBigInteger(column: 'accountable_id')->nullable()->index();

                // Table main attributes
                $table->string(column: 'account_name')->index();
                $table->string(column: 'account_number')->unique()->index();
                $table->dateTime(column: 'account_activity_period')->default(value: Carbon::now());
                $table->boolean(column: 'accepted_terms')->default(value: false);
                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::APPROVED->value,
                    Statuses::ACTIVE->value,
                    Statuses::CLOSED->value,
                    Statuses::SUSPENDED->value,
                ])->default(value: Statuses::PENDING->value);

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'accounts'
        );
    }
};
