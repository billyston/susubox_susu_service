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
            table: 'dwadieboa_susu_members',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related fields

                // Table main attributes

                // Foreign key fields

                // Timestamps (created_at/updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'dwadieboa_susu_members'
        );
    }
};
