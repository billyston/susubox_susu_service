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
            table: 'payment_instructions',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'transaction_category_id')->index()->constrained();
                $table->foreignId(column: 'account_id')->constrained()->cascadeOnDelete();
                $table->foreignId(column: 'account_customer_id')->constrained()->cascadeOnDelete();
                $table->foreignId(column: 'wallet_id')->constrained()->restrictOnDelete();

                // Table main attributes
                $table->bigInteger(column: 'amount')->default(value: 0);
                $table->bigInteger(column: 'charge')->nullable()->default(value: 0);
                $table->bigInteger(column: 'total')->nullable()->default(value: 0);
                $table->string(column: 'currency')->nullable()->default(value: 'GHS');

                $table->string(column: 'internal_reference')->index()->nullable();
                $table->enum(column: 'transaction_type', allowed: [
                    TransactionType::CREDIT->value,
                    TransactionType::DEBIT->value,
                ]);
                $table->boolean(column: 'accepted_terms')->default(value: false);
                $table->enum(column: 'approval_status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::APPROVED->value,
                    Statuses::CANCELLED->value,
                ])->default(value: Statuses::PENDING->value);
                $table->dateTime(column: 'approved_at')->nullable();
                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::ACTIVE->value,
                    Statuses::SUCCESS->value,
                    Statuses::TERMINATED->value,
                    Statuses::FAILED->value,
                ])->index()->default(value: Statuses::PENDING->value);
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
            table: 'payment_instructions'
        );
    }
};
