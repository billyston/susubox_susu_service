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
            table: 'drive_to_own_susus',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'individual_account_id')->constrained(table: 'individual_accounts')->cascadeOnDelete();
                $table->foreignId(column: 'wallet_id')->constrained(table: 'wallets');
                $table->foreignId(column: 'frequency_id')->constrained(table: 'frequencies');

                // Table main attributes
                $table->bigInteger(column: 'susu_amount');
                $table->bigInteger(column: 'initial_deposit');
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->json(column: 'extra_data')->nullable();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'drive_to_own_susus'
        );
    }
};
