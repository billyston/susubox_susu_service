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
            table: 'wallets',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'customer_id')->index()->unique()->constrained(table: 'customers')->restrictOnDelete();

                // Table main attributes
                $table->string(column: 'wallet_name');
                $table->string(column: 'wallet_number')->unique()->index();
                $table->string(column: 'network_code');
                $table->string(column: 'status')->default(value: 'active');

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
            table: 'wallets'
        );
    }
};
