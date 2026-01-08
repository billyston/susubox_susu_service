<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(
    ): void {
        Schema::create(
            table: 'fee_overrides',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->nullableMorphs(name: 'overrideable');
                $table->foreignId(column: 'fee_and_charge_id')->constrained(table: 'fees_and_charges')->cascadeOnDelete();

                // Table main attributes
                $table->string(column: 'override_type');
                $table->decimal(column: 'value', total: 12, places: 4)->nullable();
                $table->timestamp(column: 'starts_at')->nullable();
                $table->timestamp(column: 'ends_at')->nullable();
                $table->boolean(column: 'is_active')->default(true);
                $table->string(column: 'reason')->nullable();
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'fee_overrides'
        );
    }
};
