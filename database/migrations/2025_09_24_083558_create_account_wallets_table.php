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
                $table->unsignedBigInteger(column: 'account_id');

                $table->unsignedBigInteger(column: 'linked_wallet_id');

                // Table main attributes

                // Foreign key fields
                $table->foreign(columns: 'account_id')
                    ->references(columns: 'id')
                    ->on(table: 'accounts')
                    ->onDelete(action: 'cascade');

                $table->foreign(columns: 'linked_wallet_id')
                    ->references(columns: 'id')
                    ->on(table: 'linked_wallets')
                    ->onDelete(action: 'cascade');

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
