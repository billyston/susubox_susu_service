<?php

namespace Database\Seeders;

use App\Domain\Shared\Enums\DurationStatus;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class DurationTableSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        $this->truncateTable(table: 'durations');

        DB::table(
            table: 'durations',
        )->insert([[
            'resource_id' => '234e788e-3c3a-4728-b53a-37ede542e538',
            'name' => 'One Month',
            'code' => 'one-month',
            'days' => '31',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => '38032298-9c41-40aa-81da-7a664afb585b',
            'name' => 'Two Months',
            'code' => 'two-months',
            'days' => '59',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => 'a045843a-0026-4943-862a-3875358e3ce5',
            'name' => 'Three Months',
            'code' => 'three-months',
            'days' => '90',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => '593ff03c-7e7e-4126-b738-52a7d0fc09dc',
            'name' => 'Six Months',
            'code' => 'six-months',
            'days' => '181',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => 'e009b57f-0403-4d80-93f4-32fbefe0667b',
            'name' => 'Nine Months',
            'code' => 'nine-months',
            'days' => '273',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => '3054f195-687a-4041-b6df-7605fd89ead1',
            'name' => 'One Year',
            'code' => 'one-year',
            'days' => '365',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => 'f58341f4-6272-452f-b05d-0c8063e48381',
            'name' => 'Eighteen Months',
            'code' => 'eighteen-months',
            'days' => '546',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => '37faf99a-7dab-46cf-a7c2-70195d494277',
            'name' => 'Two Years',
            'code' => 'two-years',
            'days' => '730',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => 'f6fb88de-117a-48f6-9cb1-669555e852d3',
            'name' => 'Three Years',
            'code' => 'three-years',
            'days' => '1096',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => '0112ca8d-aa0e-4349-b9ca-c2d0714466fe',
            'name' => 'Four Years',
            'code' => 'four-years',
            'days' => '1461',
            'status' => DurationStatus::ACTIVE->value,
        ], [
            'resource_id' => '3df46fac-f405-41ee-8888-bbf85e6c1779',
            'name' => 'Five Years',
            'code' => 'five-years',
            'days' => '1826',
            'status' => DurationStatus::ACTIVE->value,
        ],
        ]);
    }
}
