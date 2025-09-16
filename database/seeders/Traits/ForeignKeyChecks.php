<?php

declare(strict_types=1);

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait ForeignKeyChecks
{
    protected function disableForeignKey(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }

    protected function enableForeignKey(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
