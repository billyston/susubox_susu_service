<?php

namespace Database\Seeders;

use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class FrequencyTableSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        $this->truncateTable(table: 'frequencies');

        DB::table(
            table: 'frequencies',
        )->insert([[
            'resource_id' => '74248fab-bc75-457e-8ad1-db90d8b31c92',
            'name' => 'Daily',
            'alias' => 'day',
            'code' => 'daily',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => 'ea6400da-8681-4ee2-8397-26edb32b2e95',
            'name' => 'Weekly',
            'alias' => 'week',
            'code' => 'weekly',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => 'c5737700-f5f3-418e-ad98-416dff080e26',
            'name' => 'By-Weekly',
            'alias' => 'by-week',
            'code' => 'by-weekly',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => 'aaf1f3b6-068c-4369-b9b3-08b375ca1e28',
            'name' => 'Monthly',
            'alias' => 'month',
            'code' => 'monthly',
            'description' => 'Some more descriptions goes here...',
        ],
        ]);
    }
}
