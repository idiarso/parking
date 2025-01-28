<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use Carbon\Carbon;

class KendaraanSeeder extends Seeder
{
    public function run()
    {
        // Hapus data yang ada
        Kendaraan::truncate();

        // Contoh data kendaraan motor
        $motorData = [
            [
                'nomor_plat' => 'B1234XYZ',
                'jenis_kendaraan' => 'motor',
                'pemilik' => 'Ahmad Susanto',
                'merk' => 'Honda Beat',
                'warna' => 'Merah',
                'status' => 'parkir',
                'waktu_masuk' => Carbon::now()->subHours(3),
                'waktu_keluar' => Carbon::now(),
            ],
            [
                'nomor_plat' => 'B5678ABC',
                'jenis_kendaraan' => 'motor',
                'pemilik' => 'Siti Rahayu',
                'merk' => 'Yamaha Mio',
                'warna' => 'Biru',
                'status' => 'parkir',
                'waktu_masuk' => Carbon::now()->subHours(5),
                'waktu_keluar' => Carbon::now(),
            ]
        ];

        // Contoh data kendaraan mobil
        $mobilData = [
            [
                'nomor_plat' => 'B9012DEF',
                'jenis_kendaraan' => 'mobil',
                'pemilik' => 'Budi Setiawan',
                'merk' => 'Toyota Avanza',
                'warna' => 'Putih',
                'status' => 'parkir',
                'waktu_masuk' => Carbon::now()->subHours(2),
                'waktu_keluar' => Carbon::now(),
            ],
            [
                'nomor_plat' => 'B3456GHI',
                'jenis_kendaraan' => 'mobil',
                'pemilik' => 'Dewi Kartika',
                'merk' => 'Honda CR-V',
                'warna' => 'Hitam',
                'status' => 'parkir',
                'waktu_masuk' => Carbon::now()->subHours(4),
                'waktu_keluar' => Carbon::now(),
            ]
        ];

        // Gabungkan data
        $kendaraanData = array_merge($motorData, $mobilData);

        foreach ($kendaraanData as $data) {
            // Cari slot parkir yang tersedia sesuai jenis kendaraan
            $slotParkir = SlotParkir::where('jenis_kendaraan', $data['jenis_kendaraan'])
                                    ->where('status', 'tersedia')
                                    ->first();

            // Hitung durasi parkir
            $waktuMasuk = Carbon::parse($data['waktu_masuk']);
            $waktuKeluar = Carbon::parse($data['waktu_keluar']);
            $durasiParkir = $waktuMasuk->diffInMinutes($waktuKeluar);

            // Cari tarif parkir
            $tarifParkir = TarifParkir::where('jenis_kendaraan', $data['jenis_kendaraan'])
                                      ->where('aktif', true)
                                      ->first();

            // Hitung biaya parkir
            $biayaParkir = 0;
            if ($tarifParkir) {
                // Hitung biaya per jam
                $jamParkir = ceil($durasiParkir / 60);
                $biayaParkir = $jamParkir * $tarifParkir->tarif_per_jam;
            }

            if ($slotParkir) {
                // Update status slot parkir
                $slotParkir->update(['status' => 'terisi']);

                // Buat kendaraan dengan slot parkir
                $kendaraan = Kendaraan::create(array_merge($data, [
                    'slot_parkir_id' => $slotParkir->id,
                    'durasi_parkir' => $durasiParkir,
                    'biaya_parkir' => $biayaParkir
                ]));
            } else {
                // Jika tidak ada slot tersedia, buat kendaraan tanpa slot
                $kendaraan = Kendaraan::create(array_merge($data, [
                    'durasi_parkir' => $durasiParkir,
                    'biaya_parkir' => $biayaParkir
                ]));
            }
        }
    }
}
