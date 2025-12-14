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
            table: 'durations',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields

                // Table main attributes
                $table->string(column: 'name');
                $table->string(column: 'code')->unique();
                $table->integer(column: 'days')->unique();
                $table->enum(column: 'status', allowed: [
                    Statuses::ACTIVE->value,
                    Statuses::SUSPENDED->value,
                ])->default(value: Statuses::ACTIVE->value);

                // Timestamps (created_at / updated_at) fields
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'durations'
        );
    }
};
