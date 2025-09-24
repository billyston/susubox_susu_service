<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'transaction_types',
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
                $table->string(column: 'name')
                    ->index();

                $table->string(column: 'description')
                    ->nullable();

                $table->json(column: 'meta')
                    ->nullable();

                // Foreign key fields

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'transaction_types'
        );
    }
};
