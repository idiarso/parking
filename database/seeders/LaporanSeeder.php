<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\Laporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanSeeder extends Seeder
{
    public function run()
    {
        // Hapus data yang ada
        Laporan::truncate();

        // Ambil admin untuk generated_by
        $admin = User::where('email', 'superadmin@sistemparkir.local')->first();

        // Buat laporan harian
        $tanggalMulai = Carbon::now()->subDay();
        $tanggalSelesai = Carbon::now();

        // Hitung statistik kendaraan
        $totalKendaraan = Kendaraan::whereBetween('waktu_keluar', [$tanggalMulai, $tanggalSelesai])->count();
        $kendaraanMotor = Kendaraan::whereBetween('waktu_keluar', [$tanggalMulai, $tanggalSelesai])
            ->where('jenis_kendaraan', 'motor')
            ->count();
        $kendaraanMobil = Kendaraan::whereBetween('waktu_keluar', [$tanggalMulai, $tanggalSelesai])
            ->where('jenis_kendaraan', 'mobil')
            ->count();

        // Hitung total pendapatan
        $totalPendapatan = Kendaraan::whereBetween('waktu_keluar', [$tanggalMulai, $tanggalSelesai])
            ->sum('biaya_parkir');
        $pendapatanMotor = Kendaraan::whereBetween('waktu_keluar', [$tanggalMulai, $tanggalSelesai])
            ->where('jenis_kendaraan', 'motor')
            ->sum('biaya_parkir');
        $pendapatanMobil = Kendaraan::whereBetween('waktu_keluar', [$tanggalMulai, $tanggalSelesai])
            ->where('jenis_kendaraan', 'mobil')
            ->sum('biaya_parkir');

        // Hitung statistik slot parkir
        $totalSlot = SlotParkir::count();
        $slotTerisi = SlotParkir::where('status', 'terisi')->count();
        $slotTersedia = SlotParkir::where('status', 'tersedia')->count();

        // Buat laporan
        Laporan::create([
            'jenis_laporan' => 'harian',
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'total_kendaraan' => $totalKendaraan,
            'kendaraan_motor' => $kendaraanMotor,
            'kendaraan_mobil' => $kendaraanMobil,
            'total_pendapatan' => $totalPendapatan,
            'pendapatan_motor' => $pendapatanMotor,
            'pendapatan_mobil' => $pendapatanMobil,
            'total_slot' => $totalSlot,
            'slot_terisi' => $slotTerisi,
            'slot_tersedia' => $slotTersedia,
            'generated_by' => $admin ? $admin->id : null,
            'status' => 'draft',
            'catatan' => 'Laporan harian sistem parkir otomatis'
        ]);
    }
}
