<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Enums\SusuType;
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
            table: 'susu_schemes',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table main attributes
                $table->string(column: 'name')->unique();
                $table->string(column: 'alias')->unique();
                $table->enum(column: 'type', allowed: [
                    SusuType::INDIVIDUAL->value,
                    SusuType::GROUP->value,
                    SusuType::CORPORATE->value,
                ]);
                $table->string(column: 'code');
                $table->text(column: 'description')->nullable();
                $table->enum(column: 'status', allowed: [
                    Statuses::ACTIVE->value,
                    Statuses::INACTIVE->value,
                    Statuses::SUSPENDED->value,
                ])->default(value: Statuses::ACTIVE->value);

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
            table: 'susu_schemes'
        );
    }
};
