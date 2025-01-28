<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parkir\SlotParkir;
use Illuminate\Support\Facades\DB;

class SlotParkirSeeder extends Seeder
{
    public function run()
    {
        // Hapus data sebelumnya
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SlotParkir::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data slot parkir motor
        $slotMotor = [
            ['nomor' => 'M01', 'jenis_kendaraan' => 'motor', 'status' => 'kosong', 'lokasi' => 'Gedung A Lantai 1'],
            ['nomor' => 'M02', 'jenis_kendaraan' => 'motor', 'status' => 'kosong', 'lokasi' => 'Gedung A Lantai 1'],
            ['nomor' => 'M03', 'jenis_kendaraan' => 'motor', 'status' => 'kosong', 'lokasi' => 'Gedung A Lantai 1'],
            ['nomor' => 'M04', 'jenis_kendaraan' => 'motor', 'status' => 'kosong', 'lokasi' => 'Gedung B Lantai 1'],
            ['nomor' => 'M05', 'jenis_kendaraan' => 'motor', 'status' => 'kosong', 'lokasi' => 'Gedung B Lantai 1'],
            ['nomor' => 'M06', 'jenis_kendaraan' => 'motor', 'status' => 'kosong', 'lokasi' => 'Gedung B Lantai 1'],
        ];

        // Data slot parkir mobil
        $slotMobil = [
            ['nomor' => 'A01', 'jenis_kendaraan' => 'mobil', 'status' => 'kosong', 'lokasi' => 'Gedung Utama Lantai 1'],
            ['nomor' => 'A02', 'jenis_kendaraan' => 'mobil', 'status' => 'kosong', 'lokasi' => 'Gedung Utama Lantai 1'],
            ['nomor' => 'A03', 'jenis_kendaraan' => 'mobil', 'status' => 'kosong', 'lokasi' => 'Gedung Utama Lantai 1'],
            ['nomor' => 'A04', 'jenis_kendaraan' => 'mobil', 'status' => 'kosong', 'lokasi' => 'Gedung Utama Lantai 2'],
            ['nomor' => 'A05', 'jenis_kendaraan' => 'mobil', 'status' => 'kosong', 'lokasi' => 'Gedung Utama Lantai 2'],
            ['nomor' => 'A06', 'jenis_kendaraan' => 'mobil', 'status' => 'kosong', 'lokasi' => 'Gedung Utama Lantai 2'],
        ];

        // Tambahkan beberapa slot dalam status khusus untuk testing
        $slotKhusus = [
            ['nomor' => 'R01', 'jenis_kendaraan' => 'mobil', 'status' => 'rusak', 'lokasi' => 'Gedung Utama Lantai 1', 'keterangan' => 'Kerusakan ringan pada marka'],
            ['nomor' => 'M07', 'jenis_kendaraan' => 'motor', 'status' => 'maintenance', 'lokasi' => 'Gedung B Lantai 1', 'keterangan' => 'Perbaikan sistem sensor'],
        ];

        // Gabungkan semua slot
        $semuaSlot = array_merge($slotMotor, $slotMobil, $slotKhusus);

        // Masukkan data ke database
        foreach ($semuaSlot as $slot) {
            SlotParkir::create($slot);
        }
    }
}
