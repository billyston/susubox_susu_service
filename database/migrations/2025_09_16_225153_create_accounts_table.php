<?php

declare(strict_types=1);

use App\Domain\Account\Enums\AccountType;
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
            table: 'accounts',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related / Foreign key fields
                $table->foreignId(column: 'susu_scheme_id')->unique()->constrained()->cascadeOnDelete();

                // Table main attributes
                $table->string(column: 'account_name')->index();
                $table->string(column: 'account_number')->unique()->index();
                $table->enum(column: 'account_type', allowed: [
                    AccountType::INDIVIDUAL,
                    AccountType::GROUP,
                ])->index();
                $table->boolean(column: 'accepted_terms')->default(value: false);
                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING,
                    Statuses::ACTIVE,
                    Statuses::CLOSED,
                    Statuses::SUSPENDED,
                ])->default(value: Statuses::PENDING);

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
            table: 'accounts'
        );
    }
};
