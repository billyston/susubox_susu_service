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
            table: 'individual_accounts',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'customer_id')->constrained(table: 'customers');
                $table->foreignId(column: 'susu_scheme_id')->constrained(table: 'susu_schemes');
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'individual_accounts'
        );
    }
};
