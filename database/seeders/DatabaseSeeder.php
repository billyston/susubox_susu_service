<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(
    ): void {
        $this->call(class: SusuSchemeTableSeeder::class);
        $this->call(class: FrequencyTableSeeder::class);
        $this->call(class: DurationTableSeeder::class);
        $this->call(class: StartDateTableSeeder::class);
        $this->call(class: TransactionCategoryTableSeeder::class);
        $this->call(class: FeesAndChargesTableSeeder::class);
    }
}
