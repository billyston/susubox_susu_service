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
            table: 'fees_and_charges',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'susu_scheme_id')->constrained(table: 'susu_schemes');

                // Table main attributes
                $table->string(column: 'category');
                $table->integer(column: 'collection_cycle')->nullable();
                $table->integer(column: 'settlement_cycle')->nullable();
                $table->float(column: 'commission')->nullable();
                $table->float(column: 'charge')->nullable();
                $table->float(column: 'fee')->nullable();

                // Timestamps (created_at / updated_at) fields
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'fee_and_charges'
        );
    }
};
