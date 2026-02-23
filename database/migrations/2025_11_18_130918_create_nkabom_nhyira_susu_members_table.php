<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Initiators;
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
            table: 'nkabom_nhyira_susu_members',
            callback: function (
                Blueprint $table
             ) {
                // Table ids
                $table->id();

                // Table related fields
                $table->foreignId(column: 'nkabom_nhyira_susu_id')->constrained(table: 'nkabom_nhyira_susus');
                $table->foreignId(column: 'customer_id')->constrained(table: 'customers');
                $table->foreignId(column: 'wallet_id')->constrained(table: 'wallets');

                // Table main attributes
                $table->enum(column: 'role', allowed: [
                    Initiators::ADMINISTRATOR,
                    Initiators::ORGANIZER,
                    Initiators::MEMBER,
                ])->default(value: Initiators::MEMBER);
                $table->integer(column: 'slots')->default(value: 1);
                $table->timestamp(column: 'joined_at')->useCurrent();
                $table->timestamp(column: 'activated_at')->nullable();
                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING,
                    Statuses::ACTIVE,
                    Statuses::INACTIVE,
                    Statuses::SUSPENDED,
                    Statuses::REMOVED,
                ])->default(value: Statuses::PENDING);
            });
    }

    /**
     * @return void
     */
    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'nkabom_nhyira_susu_members'
        );
    }
};
