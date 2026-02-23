<?php

declare(strict_types=1);

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
            table: 'fees_and_charges',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'susu_scheme_id')->index()->constrained(table: 'susu_schemes');

                // Table main attributes
                $table->string(column: 'event')->index();
                $table->string(column: 'calculation_type');
                $table->decimal(column: 'value', total: 12, places: 4)->nullable();
                $table->boolean(column: 'is_active')->default(value: true)->index();
                $table->timestamp(column: 'effective_from')->nullable();
                $table->timestamp(column: 'effective_to')->nullable();

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
            table: 'fee_and_charges'
        );
    }
};
