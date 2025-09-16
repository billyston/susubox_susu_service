<?php

namespace Database\Seeders;

use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class StartDateTableSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        $this->truncateTable(table: 'start_dates');

        DB::table(
            table: 'start_dates',
        )->insert([[
            'resource_id' => '0a0f1d98-728a-40c1-bfb1-848e1efee805',
            'name' => 'Today',
            'code' => 'today',
            'days' => '0',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => '05224524-25ed-4f00-acbd-86618f958d56',
            'name' => 'Next Week',
            'code' => 'next-week',
            'days' => '14',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => '68f5748e-3535-4197-9f7a-ec946f002457',
            'name' => 'Two Weeks',
            'code' => 'two-weeks',
            'days' => '21',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => '09dd895f-4291-4a86-9c45-f3c2cd8701e4',
            'name' => 'Next Month',
            'code' => 'next-month',
            'days' => '30',
            'description' => 'Some more descriptions goes here...',
        ],
        ]);
    }
}
