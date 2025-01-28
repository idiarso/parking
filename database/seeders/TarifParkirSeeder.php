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
                'jam_mulai' => '06:00',
                'jam_selesai' => '22:00',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jenis_kendaraan' => 'mobil',
                'tarif_per_jam' => 5000,
                'tarif_per_hari' => 50000,
                'jam_mulai' => '06:00',
                'jam_selesai' => '22:00',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
