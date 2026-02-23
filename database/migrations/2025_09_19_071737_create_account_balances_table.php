<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(
    ): void {
        Schema::create(
            table: 'account_balances',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id()->unique();

                // Table related fields
                $table->foreignId(column: 'account_id')->unique()->constrained(table: 'accounts')->cascadeOnDelete();

                // Table main attributes
                $table->bigInteger(column: 'ledger_balance')->default(value: 0);
                $table->bigInteger(column: 'available_balance')->default(value: 0);
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->string(column: 'last_transaction_id')->nullable();
                $table->timestamp(column: 'last_reconciled_at')->nullable();

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    /**
     * @return void
     */
    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_balances'
        );
    }
};
