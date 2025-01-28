<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifParkirSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tarif_parkir')->insert([
            [
                'jenis_kendaraan' => 'motor',
                'tarif_per_jam' => 3000,
                'tarif_per_hari' => 25000,
                'denda_per_jam' => 5000,
                'keterangan' => 'Tarif parkir motor untuk area umum',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jenis_kendaraan' => 'mobil',
                'tarif_per_jam' => 5000,
                'tarif_per_hari' => 50000,
                'denda_per_jam' => 10000,
                'keterangan' => 'Tarif parkir mobil untuk area umum',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
