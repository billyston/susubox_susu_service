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
            table: 'commission_and_charges',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')
                    ->unique()
                    ->index();

                // Table related fields
                $table->unsignedBigInteger(column: 'susu_scheme_id')
                    ->nullable();

                // Table main attributes
                $table->string(column: 'category');

                $table->integer(column: 'collection_cycle')
                    ->nullable();

                $table->integer(column: 'settlement_cycle')
                    ->nullable();

                $table->float(column: 'commission')
                    ->nullable();

                $table->float(column: 'charge')
                    ->nullable();

                $table->float(column: 'fee')
                    ->nullable();

                // Foreign key fields
                $table->foreign(columns: 'susu_scheme_id')
                    ->references(columns: 'id')
                    ->on(table: 'susu_schemes')
                    ->onDelete(action: 'cascade');

                // Timestamps (created_at / updated_at) fields
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'commission_and_charges'
        );
    }
};
