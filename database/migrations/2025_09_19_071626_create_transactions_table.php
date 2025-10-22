<?php

declare(strict_types=1);

use App\Domain\Transaction\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'transactions',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')
                    ->unique()
                    ->index();

                // Table related fields
                $table->unsignedBigInteger(column: 'account_id');

                $table->unsignedBigInteger(column: 'transaction_category_id');

                $table->unsignedBigInteger(column: 'linked_wallet_id');

                // Table main attributes
                $table->string(column: 'reference_number')
                    ->index();

                $table->integer(column: 'amount')
                    ->default(value: 0);

                $table->integer(column: 'charge')
                    ->default(value: 0);

                $table->integer(column: 'total')
                    ->default(value: 0);

                $table->string(column: 'currency')
                    ->default(value: 'GHS');

                $table->string(column: 'description')
                    ->nullable();

                $table->string(column: 'narration')
                    ->nullable();

                $table->dateTime(column: 'date');

                $table->string(column: 'status_code');

                $table->enum(column: 'status', allowed: [
                    TransactionStatus::SUCCESS->value,
                    TransactionStatus::FAILED->value,
                    TransactionStatus::REVERSED->value,
                    TransactionStatus::REFUNDED->value,
                    TransactionStatus::CANCELLED->value,
                ]);

                $table->json(column: 'extra_data')
                    ->nullable();

                // Foreign key fields
                $table->foreign(columns: 'account_id')
                    ->references(columns: 'id')
                    ->on(table: 'accounts')
                    ->onDelete(action: 'cascade');

                $table->foreign(columns: 'transaction_category_id')
                    ->references(columns: 'id')
                    ->on(table: 'transaction_categories')
                    ->onDelete(action: 'cascade');

                $table->foreign(columns: 'linked_wallet_id')
                    ->references(columns: 'id')
                    ->on(table: 'linked_wallets')
                    ->onDelete(action: 'cascade');

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'transactions'
        );
    }
};
