<?php

declare(strict_types=1);

use App\Domain\Customer\Enums\CustomerType;
use App\Domain\Shared\Enums\Statuses;
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
            table: 'account_customers',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related / Foreign key fields
                $table->foreignId(column: 'account_id')->unique()->constrained()->cascadeOnDelete();
                $table->foreignId(column: 'customer_id')->unique()->constrained()->cascadeOnDelete();
                $table->foreignId(column: 'wallet_id')->constrained()->restrictOnDelete();

                // Table main attributes
                $table->enum(column: 'customer_type', allowed: [
                    CustomerType::PRIMARY,
                    CustomerType::MEMBER,
                ])->default(value: CustomerType::PRIMARY);
                $table->timestamp(column: 'joined_at')->nullable();
                $table->enum(column: 'status', allowed: [
                    Statuses::ACTIVE,
                    Statuses::SUSPENDED,
                    Statuses::REMOVED,
                ])->default(value: Statuses::ACTIVE);

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
            table: 'account_customers'
        );
    }
};
