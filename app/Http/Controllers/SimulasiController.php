<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parkir;
use App\Models\Kendaraan;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SimulasiController extends Controller
{
    public function index()
    {
        // Histori Parkir
        $historiParkir = Parkir::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(id) as total_kendaraan'),
            DB::raw('(COUNT(id) / 50 * 100) as okupansi'),
            DB::raw('SUM(total_bayar) as pendapatan')
        )
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'desc')
        ->limit(30)
        ->get();

        // Prediksi Okupansi (contoh sederhana)
        $prediksiOkupansi = $this->hitungPrediksiOkupansi();

        // Rekomendasi Pengaturan
        $rekomendasiPengaturan = $this->generateRekomendasiPengaturan();

        return view('simulasi.index', compact(
            'historiParkir', 
            'prediksiOkupansi', 
            'rekomendasiPengaturan'
        ));
    }

    private function hitungPrediksiOkupansi()
    {
        // Prediksi okupansi berdasarkan data historis
        $historiOkupansi = Parkir::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('(COUNT(id) / 50 * 100) as okupansi')
        )
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'desc')
        ->limit(7)
        ->pluck('okupansi', 'tanggal')
        ->toArray();

        // Prediksi sederhana menggunakan moving average
        $prediksi = [];
        $keys = array_keys($historiOkupansi);
        for ($i = 0; $i < 7; $i++) {
            $tanggal = Carbon::parse($keys[0])->addDays($i)->format('Y-m-d');
            $prediksi[$tanggal] = $this->calculateMovingAverage($historiOkupansi, $i);
        }

        return $prediksi;
    }

    private function calculateMovingAverage($data, $offset)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        
        // Simple moving average dari 3 hari terakhir
        $window = array_slice($values, $offset, 3);
        return count($window) > 0 ? array_sum($window) / count($window) : 0;
    }

    private function generateRekomendasiPengaturan()
    {
        $rekomendasi = [
            (object)[
                'judul' => 'Tambah Slot Motor',
                'icon' => 'fas fa-motorcycle',
                'prioritas' => 'tinggi'
            ],
            (object)[
                'judul' => 'Optimalkan Tarif',
                'icon' => 'fas fa-money-bill-wave',
                'prioritas' => 'sedang'
            ],
            (object)[
                'judul' => 'Perbaiki Sistem Pembayaran',
                'icon' => 'fas fa-credit-card',
                'prioritas' => 'rendah'
            ]
        ];

        return $rekomendasi;
    }

    public function simulasiParkir(Request $request)
    {
        $validatedData = $request->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'jumlah_kendaraan' => 'required|integer|min:1|max:100',
            'durasi_parkir' => 'required|integer|min:1|max:24'
        ]);

        $tarifMotor = 3000;
        $tarifMobil = 5000;
        $totalSlot = 50;

        $tarif = $validatedData['jenis_kendaraan'] === 'motor' ? $tarifMotor : $tarifMobil;
        $estimasiPendapatan = $validatedData['jumlah_kendaraan'] * $tarif * $validatedData['durasi_parkir'];
        $estimasiOkupansi = ($validatedData['jumlah_kendaraan'] / $totalSlot) * 100;
        $estimasiKetersediaanSlot = $totalSlot - $validatedData['jumlah_kendaraan'];

        return response()->json([
            'estimasi_pendapatan' => $estimasiPendapatan,
            'estimasi_okupansi' => $estimasiOkupansi,
            'estimasi_ketersediaan_slot' => $estimasiKetersediaanSlot
        ]);
    }
}
