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
            table: 'account_wallets',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related fields

                // Table main attributes

                // Foreign key fields
                $table->foreignId(column: 'account_id')
                    ->constrained(table: 'accounts')
                    ->cascadeOnDelete();

                $table->foreignId(column: 'linked_wallet_id')
                    ->constrained(table: 'linked_wallets')
                    ->cascadeOnDelete();

                // Timestamps (created_at/updated_at) fields
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_wallets'
        );
    }
};
