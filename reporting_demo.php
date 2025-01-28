<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Fungsi untuk simulasi data parkir
function simulasiDataParkir($jumlahKendaraan = 50) {
    $jenisKendaraan = ['motor', 'mobil'];
    $kodeWilayah = ['B', 'D', 'F', 'H', 'K'];

    // Disable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    // Clear related tables
    DB::table('slot_parkir')->truncate();
    DB::table('kendaraan')->truncate();

    for ($i = 0; $i < $jumlahKendaraan; $i++) {
        $jenis = $jenisKendaraan[array_rand($jenisKendaraan)];
        $waktuMasuk = Carbon::now()->subDays(rand(0, 30))->subHours(rand(1, 12));
        $waktuKeluar = (clone $waktuMasuk)->addHours(rand(1, 5));
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

// Simulasi data parkir
simulasiDataParkir(100);

// Inisialisasi kontroler laporan
$laporanController = new App\Http\Controllers\Parkir\LaporanController();

echo " LAPORAN PARKIR SISTEM DEMO \n\n";

// Laporan Harian
echo "1. LAPORAN HARIAN:\n";
$laporanHarian = $laporanController->laporanHarian();
$dataHarian = $laporanHarian->getData();
print_r([
    'Kendaraan Masuk' => $dataHarian->kendaraan_masuk,
    'Kendaraan Keluar' => $dataHarian->kendaraan_keluar,
    'Pendapatan Harian' => 'Rp. ' . number_format($dataHarian->pendapatan_harian, 0, ',', '.'),
    'Detail Kendaraan' => $dataHarian->detail_kendaraan
]);

echo "\n2. LAPORAN BULANAN:\n";
$laporanBulanan = $laporanController->laporanBulanan();
$dataBulanan = $laporanBulanan->getData();
print_r([
    'Total Kendaraan' => $dataBulanan->total_kendaraan,
    'Pendapatan Bulanan' => 'Rp. ' . number_format($dataBulanan->pendapatan_bulanan, 0, ',', '.'),
    'Detail Bulanan' => $dataBulanan->detail_bulanan
]);

echo "\n3. LAPORAN PENDAPATAN:\n";
$laporanPendapatan = $laporanController->laporanPendapatan();
$dataPendapatan = $laporanPendapatan->getData();
print_r([
    'Pendapatan Total' => 'Rp. ' . number_format($dataPendapatan->pendapatan_total, 0, ',', '.'),
    'Proyeksi Pendapatan' => $dataPendapatan->proyeksi_pendapatan
]);
