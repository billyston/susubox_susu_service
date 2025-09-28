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
            'code' => 'SU-DSS001',
            'description' => 'The Susubox Daily Susu Savings scheme offers a user-friendly approach to daily susu savings, mimicking the traditional susu model.',
            'status' => 'active',
        ], [
            'resource_id' => '06f8fdd0-8a33-4483-adfb-6643d1f5797d',
            'name' => 'Biz Susu Savings',
            'alias' => 'Biz Susu',
            'code' => 'SU-BSS002',
            'description' => 'Biz Susu Savings is tailored for small business owners, facilitating daily, weekly, or monthly contributions to fuel business growth.',
            'status' => 'active',
        ], [
            'resource_id' => '805df9db-1f9b-4fac-979e-d00572807092',
            'name' => 'Goal Getter Savings',
            'alias' => 'Goal Getter',
            'code' => 'SU-GGS003',
            'description' => 'Goal Getter Savings is a tailored financial tool within Susubox, aiding individuals in achieving specific milestones like education funding or business ventures.',
            'status' => 'active',
        ], [
            'resource_id' => '91b1b2af-a21b-43aa-8bd1-da23cf4340ba',
            'name' => 'Flexy Susu Savings',
            'alias' => 'Flexy Susu',
            'code' => 'SU-FSS004',
            'description' => 'Flexy Susu Savings is a unique feature in the Susubox ecosystem, offering individuals a flexible and dailyized savings approach.',
            'status' => 'active',
        ], [
            'resource_id' => '57aee8a8-f080-4ca1-8f1a-7fc6f07d62e4',
            'name' => 'Susu For Bills',
            'alias' => 'Bills Susu',
            'code' => 'SU-SFB005',
            'description' => 'Susu for Bills (SFB) enables users to save daily towards recurring expenses, ensuring bills are paid with ease and consistency.',
            'status' => 'inactive',
        ], [
            'resource_id' => '3c4f0568-2427-4ca6-9918-aa70f01b8802',
            'name' => 'Drive2Own',
            'alias' => 'SusuBox Drive2Own',
            'code' => 'SU-D2W006',
            'description' => 'SusuBox Drive2Own empowers commercial drivers to save daily and gradually finance their own vehicles, turning work into sustainable ownership and financial independence.',
            'status' => 'inactive',
        ], [
            'resource_id' => '40890181-f0ff-4048-8d29-72c2c724954f',
            'name' => 'Nkabom Nhyira',
            'alias' => 'Nkabom Nhyira Group Susu',
            'code' => 'SU-NGS007',
            'description' => 'SusuBox’s “Nkabom Nhyira” Group Susu digitizes traditional group savings, enabling members to make fixed contributions over set periods with pooled funds disbursed in cycles.',
            'status' => 'inactive',
        ], [
            'resource_id' => 'a3ae4243-e31d-4ca5-8d75-02bab8257cbb',
            'name' => 'Dwadieboa',
            'alias' => 'Dwadieboa Annual Group Savings',
            'code' => 'SU-DGS008',
            'description' => '“Dwadieboa” Group Savings provides trade groups and associations with a structured, long-term savings system that fosters financial growth, business support, and community bonding.',
            'status' => 'inactive',
        ],
        ]);
    }
}
