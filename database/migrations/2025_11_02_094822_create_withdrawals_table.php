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
            table: 'withdrawals',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                $table->uuid(column: 'resource_id')
                    ->unique()
                    ->index();

                // Table related fields
                $table->unsignedBigInteger(column: 'account_id')
                    ->index();

                // Table main attributes
                $table->integer(column: 'amount')
                    ->nullable(value: false)
                    ->default(value: 0);

                $table->integer(column: 'charge')
                    ->nullable()
                    ->default(value: 0);

                $table->integer(column: 'total')
                    ->nullable(value: false)
                    ->default(value: 0);

                $table->string(column: 'currency')
                    ->nullable()
                    ->default(value: 'GHS');

                $table->boolean(column: 'accepted_terms')
                    ->default(value: false);

                $table->enum(column: 'status', allowed: [
                    TransactionStatus::PENDING->value,
                    TransactionStatus::APPROVED->value,
                    TransactionStatus::CANCELLED->value,
                ])
                    ->default(value: TransactionStatus::PENDING->value);

                // Foreign key fields
                $table->foreign(columns: 'account_id')
                    ->references(columns: 'id')
                    ->on(table: 'accounts')
                    ->onDelete(action: 'cascade');

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'withdrawals'
        );
    }
};
