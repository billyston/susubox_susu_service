<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Statuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

                // What is the payment for (Polymorphic: IndividualAccount, GroupAccount)
                $table->string(column: 'for_type')->index();
                $table->unsignedBigInteger(column: 'for_id')->index();

                // Who initiated the payment (Polymorphic: Customer, GroupMember)
                $table->string(column: 'initiated_by_type')->index();
                $table->unsignedBigInteger(column: 'initiated_by_id')->index();

                // Table related fields
                $table->foreignId(column: 'account_id')->index()->constrained(table: 'accounts');
                $table->foreignId(column: 'transaction_category_id')->index()->constrained(table: 'transaction_categories');
                $table->foreignId(column: 'wallet_id')->constrained(table: 'wallets');

                // Table main attributes
                $table->integer(column: 'amount')->default(value: 0);
                $table->integer(column: 'charge')->nullable()->default(value: 0);
                $table->integer(column: 'total')->nullable()->default(value: 0);
                $table->string(column: 'currency')->nullable()->default(value: 'GHS');

                $table->string(column: 'internal_reference')->index()->nullable();
                $table->enum(column: 'approval_status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::APPROVED->value,
                    Statuses::CANCELLED->value,
                ])->default(value: Statuses::PENDING->value);
                $table->json(column: 'extra_data')->nullable();
                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::APPROVED->value,
                    Statuses::CANCELLED->value,
                    Statuses::SUCCESS->value,
                    Statuses::FAILED->value,
                ])->index()->default(value: Statuses::PENDING->value);

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'payment_instructions'
        );
    }
};
