<?php

namespace App\Services;

use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ParkingAnalyticsService
{
    /**
     * Analisis Kompleks Performa Parkir
     */
    public function performaKeseluruhan()
    {
        // Periode analisis (3 bulan terakhir)
        $tigaBulanTerakhir = Carbon::now()->subMonths(3);

        // Total kendaraan
        $totalKendaraan = Kendaraan::where('waktu_masuk', '>=', $tigaBulanTerakhir)->count();

        // Analisis berdasarkan jenis kendaraan
        $analisisKendaraan = Kendaraan::where('waktu_masuk', '>=', $tigaBulanTerakhir)
            ->select(
                'jenis_kendaraan',
                DB::raw('COUNT(*) as total_kendaraan'),
                DB::raw('AVG(durasi_parkir) as durasi_rata_rata'),
                DB::raw('SUM(biaya_parkir) as total_pendapatan'),
                DB::raw('MIN(durasi_parkir) as durasi_minimal'),
                DB::raw('MAX(durasi_parkir) as durasi_maksimal')
            )
            ->groupBy('jenis_kendaraan')
            ->get();

        // Analisis waktu parkir
        $distribusiWaktuParkir = $this->analisisDistribusiWaktu($tigaBulanTerakhir);

        // Proyeksi kebutuhan slot
        $proyeksiSlot = $this->proyeksiKebutuhanSlot();

        return [
            'total_kendaraan' => $totalKendaraan,
            'analisis_kendaraan' => $analisisKendaraan,
            'distribusi_waktu_parkir' => $distribusiWaktuParkir,
            'proyeksi_slot' => $proyeksiSlot
        ];
    }

    /**
     * Analisis Distribusi Waktu Parkir
     */
    private function analisisDistribusiWaktu(Carbon $mulaiDari)
    {
        return Kendaraan::where('waktu_masuk', '>=', $mulaiDari)
            ->select(
                DB::raw('HOUR(waktu_masuk) as jam'),
                DB::raw('COUNT(*) as total_kendaraan'),
                DB::raw('AVG(biaya_parkir) as rata_rata_biaya')
            )
            ->groupBy(DB::raw('HOUR(waktu_masuk)'))
            ->orderBy('jam')
            ->get();
    }

    /**
     * Proyeksi Kebutuhan Slot Parkir
     */
    private function proyeksiKebutuhanSlot()
    {
        // Rata-rata penggunaan slot per hari
        $rataRataHarian = Kendaraan::select(
            DB::raw('DATE(waktu_masuk) as tanggal'),
            DB::raw('COUNT(*) as total_kendaraan')
        )
        ->groupBy(DB::raw('DATE(waktu_masuk)'))
        ->get()
        ->avg('total_kendaraan');

        // Ketersediaan slot saat ini
        $totalSlot = SlotParkir::count();
        $slotTerisi = SlotParkir::where('status', 'terisi')->count();

        return [
            'rata_rata_kendaraan_harian' => round($rataRataHarian, 2),
            'total_slot' => $totalSlot,
            'slot_terisi' => $slotTerisi,
            'persentase_penggunaan' => round(($slotTerisi / $totalSlot) * 100, 2)
        ];
    }

    /**
     * Prediksi Pendapatan Mendatang
     */
    public function prediksiPendapatan($bulanKedepan = 3)
    {
        // Pendapatan 3 bulan terakhir
        $pendapatanTerakhir = Kendaraan::where('waktu_masuk', '>=', Carbon::now()->subMonths(3))
            ->sum('biaya_parkir');

        // Rata-rata pendapatan bulanan
        $rataRataPendapatan = $pendapatanTerakhir / 3;

        // Proyeksi pendapatan
        $proyeksiPendapatan = $rataRataPendapatan * $bulanKedepan;

        return [
            'pendapatan_3_bulan_terakhir' => $pendapatanTerakhir,
            'rata_rata_pendapatan_bulanan' => $rataRataPendapatan,
            'proyeksi_pendapatan' => $proyeksiPendapatan
        ];
    }

    /**
     * Identifikasi Pola Penggunaan Parkir
     */
    public function polaPenggunaan()
    {
        // Hari tersibuk
        $hariTersibuk = Kendaraan::select(
            DB::raw('DAYNAME(waktu_masuk) as hari'),
            DB::raw('COUNT(*) as total_kendaraan')
        )
        ->groupBy(DB::raw('DAYNAME(waktu_masuk)'))
        ->orderBy('total_kendaraan', 'desc')
        ->first();

        // Jam tersibuk
        $jamTersibuk = Kendaraan::select(
            DB::raw('HOUR(waktu_masuk) as jam'),
            DB::raw('COUNT(*) as total_kendaraan')
        )
        ->groupBy(DB::raw('HOUR(waktu_masuk)'))
        ->orderBy('total_kendaraan', 'desc')
        ->first();

        return [
            'hari_tersibuk' => $hariTersibuk,
            'jam_tersibuk' => $jamTersibuk
        ];
    }
}
