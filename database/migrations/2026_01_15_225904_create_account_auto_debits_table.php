<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Initiators;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'account_auto_debits',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique();

                // Table related fields
                $table->string(column: 'debitable_type');
                $table->unsignedBigInteger(column: 'debitable_id');

                // Table main attributes
                $table->enum(column: 'action', allowed: [
                    'enabled',
                    'disabled',
                ]);

                $table->boolean(column: 'from_state');
                $table->boolean(column: 'to_state');

                $table->timestamp(column: 'requested_at');
                $table->timestamp(column: 'effective_at')->nullable();

                $table->enum(column: 'initiator', allowed: [
                    Initiators::CUSTOMER->value,
                    Initiators::ADMINISTRATOR->value,
                ])->default(
                    Initiators::CUSTOMER->value
                );
                $table->string(column: 'initiator_id')->nullable();

                // Foreign key fields

                // Timestamps (created_at / updated_at) fields
                $table->timestamps();

                $table->index([
                    'debitable_type',
                    'debitable_id',
                ]);
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'account_auto_debits'
        );
    }
};
