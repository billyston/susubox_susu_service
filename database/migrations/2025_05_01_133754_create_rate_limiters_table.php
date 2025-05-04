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
            table: 'rate_limiters',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related fields

                // Table main attributes
                $table->string(column: 'key')->comment(comment: 'The rate limit key (e.g., user ID, IP, API key)');
                $table->string(column: 'group')->comment(comment: 'Rate limit group / endpoint name');
                $table->integer(column: 'attempts')->default(value: 0);
                $table->timestamp(column: 'reset_at')->nullable();
                $table->timestamp(column: 'expires_at')->nullable();

                $table->index(['key', 'group']);
                $table->index(columns: 'expires_at');

                // Foreign key fields

                // Timestamps (created_at/updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'rate_limiters'
        );
    }
};
