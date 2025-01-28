<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Parkir\SlotParkir;

class SlotParkirSeeder extends Seeder
{
    public function run()
    {
        // Hapus data yang ada
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SlotParkir::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat slot motor
        for ($i = 1; $i <= 20; $i++) {
            SlotParkir::create([
                'kode_slot' => 'M' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'jenis_kendaraan' => 'motor',
                'status' => 'tersedia'
            ]);
        }

        // Buat slot mobil
        for ($i = 1; $i <= 10; $i++) {
            SlotParkir::create([
                'kode_slot' => 'C' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'jenis_kendaraan' => 'mobil',
                'status' => 'tersedia'
            ]);
        }
    }
}
