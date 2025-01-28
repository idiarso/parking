<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\TarifParkirSeeder;
use Database\Seeders\SlotParkirSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\KendaraanSeeder;
use Database\Seeders\LaporanSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TarifParkirSeeder::class,
            SlotParkirSeeder::class,
            KendaraanSeeder::class,
            LaporanSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
