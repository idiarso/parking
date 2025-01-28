<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\ParkingAnalyticsService;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use Database\Seeders\SlotParkirSeeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Seed parking slots
$slotSeeder = new SlotParkirSeeder();
$slotSeeder->run();

// Fungsi untuk simulasi data yang lebih komprehensif
function simulasiDataAnalitik($jumlahKendaraan = 500) {
    $jenisKendaraan = ['motor', 'mobil'];
    $kodeWilayah = ['B', 'D', 'F', 'H', 'K'];

    // Disable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    // Clear kendaraan table
    DB::table('kendaraan')->truncate();

    // Simulasi data dalam rentang 3 bulan terakhir
    for ($i = 0; $i < $jumlahKendaraan; $i++) {
        $jenis = $jenisKendaraan[array_rand($jenisKendaraan)];
        
        // Distribusi waktu yang lebih realistis
        $waktuMasuk = Carbon::now()
            ->subMonths(rand(0, 3))
            ->subDays(rand(0, 90))
            ->setHour(rand(0, 23))
            ->setMinute(rand(0, 59));
        
        $waktuKeluar = (clone $waktuMasuk)->addHours(rand(1, 6));
        $durasi = $waktuMasuk->diffInHours($waktuKeluar);
        
        $tarif = $jenis == 'motor' ? 3000 : 5000;
        $biaya = $durasi * $tarif;

        Kendaraan::create([
            'plat_nomor' => $kodeWilayah[array_rand($kodeWilayah)] . ' ' . 
                            str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . 
                            ' ' . strtoupper(substr(md5(rand()), 0, 3)),
            'jenis_kendaraan' => $jenis,
            'waktu_masuk' => $waktuMasuk,
            'waktu_keluar' => $waktuKeluar,
            'durasi_parkir' => $durasi,
            'biaya_parkir' => $biaya,
            'status' => 'keluar'
        ]);
    }

    // Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
}

// Simulasi data
simulasiDataAnalitik(500);

// Inisialisasi layanan analitik
$analyticsService = new ParkingAnalyticsService();

echo "🚗 ANALISIS PARKIR KOMPREHENSIF 🚗\n\n";

// 1. Performa Keseluruhan
echo "1. PERFORMA KESELURUHAN:\n";
$performaKeseluruhan = $analyticsService->performaKeseluruhan();
print_r([
    'Total Kendaraan' => $performaKeseluruhan['total_kendaraan'],
    'Analisis Kendaraan' => $performaKeseluruhan['analisis_kendaraan'],
    'Proyeksi Slot' => $performaKeseluruhan['proyeksi_slot']
]);

// 2. Prediksi Pendapatan
echo "\n2. PREDIKSI PENDAPATAN:\n";
$prediksiPendapatan = $analyticsService->prediksiPendapatan();
print_r([
    'Pendapatan 3 Bulan Terakhir' => 'Rp. ' . number_format($prediksiPendapatan['pendapatan_3_bulan_terakhir'], 0, ',', '.'),
    'Rata-rata Pendapatan Bulanan' => 'Rp. ' . number_format($prediksiPendapatan['rata_rata_pendapatan_bulanan'], 0, ',', '.'),
    'Proyeksi Pendapatan' => 'Rp. ' . number_format($prediksiPendapatan['proyeksi_pendapatan'], 0, ',', '.')
]);

// 3. Pola Penggunaan
echo "\n3. POLA PENGGUNAAN:\n";
$polaPenggunaan = $analyticsService->polaPenggunaan();
print_r([
    'Hari Tersibuk' => $polaPenggunaan['hari_tersibuk'],
    'Jam Tersibuk' => $polaPenggunaan['jam_tersibuk']
]);
