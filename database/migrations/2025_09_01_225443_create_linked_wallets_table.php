<?php

declare(strict_types=1);

use Domain\Customer\Enums\LinkedWalletStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'linked_wallets',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')
                    ->unique()
                    ->index();

                // Table related fields
                $table->unsignedBigInteger(column: 'customer_id');

                // Table main attributes
                $table->string(column: 'wallet_name')
                    ->nullable();

                $table->string(column: 'wallet_number')
                    ->unique()
                    ->index();

                $table->string(column: 'network_code');

                $table->string(column: 'status')->default(
                    value: 'active'
                );

                // Foreign key field
                $table->foreign(columns: 'customer_id')
                    ->references(columns: 'id')
                    ->on(table: 'customers')
                    ->onDelete(action: 'cascade');

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'linked_wallets'
        );
    }
};
