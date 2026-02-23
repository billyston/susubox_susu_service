<?php

declare(strict_types=1);

use App\Application\Shared\Helpers\Helpers;
use Carbon\Carbon;
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
            table: 'nkabom_nhyira_susus',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'account_id')->constrained(table: 'accounts')->cascadeOnDelete();

                // Table main attributes
                $table->integer(column: 'min_slot_per_member')->default(value: 1);
                $table->integer(column: 'max_slot_per_member')->default(value: 5);
                $table->boolean(column: 'auto_payout')->default(value: false);
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
            table: 'nkabom_nhyira_susus'
        );
    }
};
