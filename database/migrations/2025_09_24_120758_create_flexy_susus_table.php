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
            table: 'flexy_susus',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'individual_account_id')->constrained(table: 'individual_accounts')->cascadeOnDelete();
                $table->foreignId(column: 'wallet_id')->constrained(table: 'wallets');

                // Table main attributes
                $table->bigInteger(column: 'initial_deposit');
                $table->string(column: 'currency')->default(value: 'GHS');
                $table->boolean(column: 'is_collateralized')->default(value: false);
                $table->enum(column: 'withdrawal_status', allowed: [
                    Statuses::ACTIVE->value,
                    Statuses::LOCKED->value,
                ])->default(value: Statuses::ACTIVE->value);
                $table->json(column: 'extra_data')->nullable();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'flexy_susus'
        );
    }
};
