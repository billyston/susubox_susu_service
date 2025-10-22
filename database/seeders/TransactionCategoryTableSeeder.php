<?php

namespace Database\Seeders;

use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class TransactionCategoryTableSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        $this->truncateTable(table: 'transaction_categories');

        DB::table(table: 'transaction_categories')->insert([[
            'resource_id' => '3f6dd164-6191-42e0-9ad2-9ba709460835',
            'name' => 'Recurring Deposit',
            'alias' => 'recurring-deposit',
            'code' => 'TXN-01',
        ], [
            'resource_id' => '157a364b-be1d-43dc-b349-e99c952a838e',
            'name' => 'Direct Deposit',
            'alias' => 'direct-deposit',
            'code' => 'TXN-02',
        ], [
            'resource_id' => '9739606d-e8a8-4a75-b8ca-06cd2af5774a',
            'name' => 'Settlement',
            'alias' => 'settlement',
            'code' => 'TXN-03',
        ], [
            'resource_id' => '68dd39c9-73c7-4fc6-af55-bfbb0c893f2a',
            'name' => 'Withdrawal',
            'alias' => 'withdrawal',
            'code' => 'TXN-04',
        ],
        ]);
    }
}
