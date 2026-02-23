<?php

declare(strict_types=1);

use App\Application\Shared\Helpers\Helpers;
use App\Domain\Shared\Enums\Statuses;
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
            table: 'biz_susus',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();
                $table->uuid(column: 'resource_id')->unique()->index();

                // Table related fields
                $table->foreignId(column: 'account_id')->constrained(table: 'accounts')->cascadeOnDelete();

                // Table main attributes
                $table->date(column: 'start_date')->default(value: Carbon::today());
                $table->date(column: 'end_date')->default(value: Helpers::getEndCollectionDate());
                $table->boolean(column: 'is_collateralized')->default(value: false);
                $table->enum(column: 'payout_status', allowed: [
                    Statuses::ACTIVE->value,
                    Statuses::LOCKED->value,
                ])->default(value: Statuses::ACTIVE->value);
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
            table: 'biz_susus'
        );
    }
};
