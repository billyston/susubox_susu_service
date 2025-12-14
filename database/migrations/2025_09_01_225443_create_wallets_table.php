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
            table: 'wallets',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'customer_id')->index()->constrained(table: 'customers');

                // Table main attributes
                $table->string(column: 'wallet_name');
                $table->string(column: 'wallet_number')->unique()->index();
                $table->string(column: 'network_code');
                $table->string(column: 'status')->default(value: 'active');

                // Constraints
                $table->unique(['customer_id', 'wallet_number']);

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();

                $table->softDeletes();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'wallets'
        );
    }
};
