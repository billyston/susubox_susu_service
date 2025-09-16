<?php

declare(strict_types=1);

use Domain\Shared\Enums\SusuSchemeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'susu_schemes',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')
                    ->unique()
                    ->index();

                // Table related fields

                // Table main attributes
                $table->string(column: 'name')->unique();

                $table->string(column: 'alias')->unique();

                $table->string(column: 'code');

                $table->string(column: 'description')->nullable();

                $table->enum(column: 'status', allowed: [
                    SusuSchemeStatus::ACTIVE->value,
                    SusuSchemeStatus::INACTIVE->value,
                    SusuSchemeStatus::SUSPENDED->value,
                ])->default(
                    value: SusuSchemeStatus::ACTIVE->value,
                );

                // Foreign key fields

                // Timestamps (created_at / updated_at) fields
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'susu_schemes'
        );
    }
};
