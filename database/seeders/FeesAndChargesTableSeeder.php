<?php

namespace Database\Seeders;

use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class FeesAndChargesTableSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        $this->truncateTable(table: 'fees_and_charges');

        DB::table(table: 'fees_and_charges')->insert([
            /*
             |--------------------------------------------------------------------------
             | Daily Susu – Unit Commission on Collection
             |--------------------------------------------------------------------------
             */
            [
                'resource_id' => 'e962db4c-d1bf-49d3-9872-3f48da122a7b',
                'susu_scheme_id' => 1,

                'event' => 'collection',
                'calculation_type' => 'unit',
                'value' => 1.0000,

                'is_active' => true,
                'effective_from' => now(),
                'effective_to' => null,
            ],

            /*
             |--------------------------------------------------------------------------
             | Percentage Charges (3.2%) – Collection
             |--------------------------------------------------------------------------
             */
            [
                'resource_id' => 'b9970797-231b-4365-bcb9-6902261218c5',
                'susu_scheme_id' => 2,

                'event' => 'collection',
                'calculation_type' => 'percentage',
                'value' => 0.0320,

                'is_active' => true,
                'effective_from' => now(),
                'effective_to' => null,
            ],
            [
                'resource_id' => '4211acc6-2e51-414d-912e-478c1857c2f3',
                'susu_scheme_id' => 3,

                'event' => 'collection',
                'calculation_type' => 'percentage',
                'value' => 0.0320,

                'is_active' => true,
                'effective_from' => now(),
                'effective_to' => null,
            ],
            [
                'resource_id' => 'f8a593a5-b3a7-441b-bbd1-c697036a7789',
                'susu_scheme_id' => 4,

                'event' => 'collection',
                'calculation_type' => 'percentage',
                'value' => 0.0320,

                'is_active' => true,
                'effective_from' => now(),
                'effective_to' => null,
            ],
            [
                'resource_id' => 'dc9d7d5a-bbc0-4fd5-a7f5-82aba335f7af',
                'susu_scheme_id' => 5,

                'event' => 'collection',
                'calculation_type' => 'percentage',
                'value' => 0.0320,

                'is_active' => true,
                'effective_from' => now(),
                'effective_to' => null,
            ],
            [
                'resource_id' => 'a3a6a0a6-d657-4e1a-b00d-bb02d9a9293e',
                'susu_scheme_id' => 6,

                'event' => 'collection',
                'calculation_type' => 'percentage',
                'value' => 0.0320,

                'is_active' => true,
                'effective_from' => now(),
                'effective_to' => null,
            ],
        ]);
    }
}

