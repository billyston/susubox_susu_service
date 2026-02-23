<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionType;
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
            table: 'transactions',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'transaction_category_id')->constrained(table: 'transaction_categories');
                $table->foreignId(column: 'payment_instruction_id')->constrained(table: 'payment_instructions');
                $table->foreignId(column: 'account_id')->constrained(table: 'accounts');
                $table->foreignId(column: 'wallet_id')->constrained(table: 'wallets');

                // Table main attributes
                $table->enum(column: 'transaction_type', allowed: [
                    TransactionType::CREDIT->value,
                    TransactionType::DEBIT->value,
                ]);
                $table->string(column: 'reference_number')->index();
                $table->bigInteger(column: 'amount')->default(value: 0);
                $table->bigInteger(column: 'charge')->default(value: 0);
                $table->bigInteger(column: 'total')->default(value: 0);
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->string(column: 'description')->nullable();
                $table->string(column: 'narration')->nullable();
                $table->dateTime(column: 'date');
                $table->string(column: 'status_code');
                $table->enum(column: 'status', allowed: [
                    Statuses::SUCCESS->value,
                    Statuses::FAILED->value,
                    Statuses::REVERSED->value,
                    Statuses::REFUNDED->value,
                    Statuses::CANCELLED->value,
                ]);
                $table->json(column: 'metadata')->nullable();

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
            table: 'transactions'
        );
    }
};
