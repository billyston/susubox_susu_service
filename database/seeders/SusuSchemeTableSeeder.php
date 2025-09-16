<?php

namespace Database\Seeders;

use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class SusuSchemeTableSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        $this->truncateTable(table: 'susu_schemes');

        DB::table(
            table: 'susu_schemes',
        )->insert([[
            'resource_id' => '20aac17e-ff63-4d0d-96b3-49007925876f',
            'name' => 'Daily Susu Savings',
            'alias' => 'Daily Susu',
            'code' => 'SSB-DSS001',
            'description' => 'The Susubox Daily Susu Savings scheme offers a user-friendly approach to daily susu savings, mimicking the traditional susu model.',
            'status' => 'active',
        ], [
            'resource_id' => '06f8fdd0-8a33-4483-adfb-6643d1f5797d',
            'name' => 'Biz Susu Savings',
            'alias' => 'Biz Susu',
            'code' => 'SSB-BSS002',
            'description' => 'Biz Susu Savings is tailored for small business owners, facilitating daily, weekly, or monthly contributions to fuel business growth.',
            'status' => 'active',
        ], [
            'resource_id' => '805df9db-1f9b-4fac-979e-d00572807092',
            'name' => 'Goal Getter Savings',
            'alias' => 'Goal Getter',
            'code' => 'SSB-GGS003',
            'description' => 'Goal Getter Savings is a tailored financial tool within Susubox, aiding individuals in achieving specific milestones like education funding or business ventures.',
            'status' => 'active',
        ], [
            'resource_id' => '91b1b2af-a21b-43aa-8bd1-da23cf4340ba',
            'name' => 'Flexy Susu Savings',
            'alias' => 'Flexy Susu',
            'code' => 'SSB-FSS004',
            'description' => 'Flexy Susu Savings is a unique feature in the Susubox ecosystem, offering individuals a flexible and dailyized savings approach.',
            'status' => 'active',
        ], [
            'resource_id' => '7f1d6072-7bcc-417e-b8ea-06a716660d55',
            'name' => 'Group Susu Savings',
            'alias' => 'Group Susu',
            'code' => 'SSB-GSS005',
            'description' => 'Some more descriptions goes here...',
            'status' => 'inactive',
        ], [
            'resource_id' => '57aee8a8-f080-4ca1-8f1a-7fc6f07d62e4',
            'name' => 'Susu For Bills',
            'alias' => 'Bills Susu',
            'code' => 'SSB-SFB006',
            'description' => 'Some more descriptions goes here...',
            'status' => 'inactive',
        ],
        ]);
    }
}
