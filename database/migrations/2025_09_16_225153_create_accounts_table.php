<?php

declare(strict_types=1);

use App\Common\Helpers\Helpers;
use Domain\Susu\Enums\Account\AccountStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'accounts',
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

                $table->unsignedBigInteger(column: 'susu_scheme_id');

                $table->unsignedBigInteger(column: 'frequency_id')
                    ->nullable();

                // Table main attributes
                $table->string(column: 'account_name')
                    ->nullable(value: false)
                    ->index();

                $table->string(column: 'account_number')
                    ->unique()
                    ->index();

                $table->string(column: 'purpose')
                    ->nullable();

                $table->integer(column: 'amount');

                $table->string(column: 'currency')
                    ->default(value: 'GHS');

                $table->date(column: 'start_date')
                    ->default(value: Carbon::today());

                $table->date(column: 'end_date')
                    ->default(value: Helpers::getEndCollectionDate());

                $table->dateTime(column: 'account_activity_period')
                    ->default(value: Carbon::now());

                $table->boolean(column: 'accepted_terms')
                    ->default(value: false);

                $table->json(column: 'extra_data')
                    ->nullable();

                $table->enum(column: 'status', allowed: [
                    AccountStatus::PENDING->value,
                    AccountStatus::APPROVED->value,
                    AccountStatus::ACTIVE->value,
                    AccountStatus::CLOSED->value,
                    AccountStatus::SUSPENDED->value,
                ])->default(
                    value: AccountStatus::PENDING->value,
                );

                // Foreign key fields
                $table->foreign(columns: 'customer_id')
                    ->references(columns: 'id')
                    ->on(table: 'customers')
                    ->onDelete(action: 'cascade');

                $table->foreign(columns: 'susu_scheme_id')
                    ->references(columns: 'id')
                    ->on(table: 'susu_schemes')
                    ->onDelete(action: 'cascade');

                $table->foreign(columns: 'frequency_id')
                    ->references(columns: 'id')
                    ->on(table: 'frequencies')
                    ->onDelete(action: 'cascade');

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'accounts'
        );
    }
};
