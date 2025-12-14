<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\Role;
use App\Domain\Shared\Enums\Statuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
                    Role::ADMIN->value,
                    Role::ORGANIZER->value,
                    Role::MEMBER->value,
                ])->default(value: Role::MEMBER->value);
                $table->integer(column: 'slots')->default(value: 1);
                $table->timestamp(column: 'joined_at')->useCurrent();
                $table->timestamp(column: 'activated_at')->nullable();
                $table->enum(column: 'status', allowed: [
                    Statuses::PENDING->value,
                    Statuses::ACTIVE->value,
                    Statuses::INACTIVE->value,
                    Statuses::SUSPENDED->value,
                    Statuses::REMOVED->value,
                ])->default(value: Statuses::PENDING->value);
            });
    }

    public function down(
    ): void {
        Schema::dropIfExists(
            table: 'nkabom_nhyira_susu_members'
        );
    }
};
