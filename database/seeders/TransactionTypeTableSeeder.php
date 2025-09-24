<?php

namespace Database\Seeders;

use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class TransactionTypeTableSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        $this->truncateTable(table: 'transaction_types');

        DB::table(table: 'transaction_types')->insert([[
            'resource_id' => '3f6dd164-6191-42e0-9ad2-9ba709460835',
            'name' => 'Recurring Deposit',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => '157a364b-be1d-43dc-b349-e99c952a838e',
            'name' => 'Direct Deposit',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => '9739606d-e8a8-4a75-b8ca-06cd2af5774a',
            'name' => 'settlement',
            'description' => 'Some more descriptions goes here...',
        ], [
            'resource_id' => '68dd39c9-73c7-4fc6-af55-bfbb0c893f2a',
            'name' => 'Withdrawal',
            'description' => 'Some more descriptions goes here...',
        ],
        ]);
    }
}
