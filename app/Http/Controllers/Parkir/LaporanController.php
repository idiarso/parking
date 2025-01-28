<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\Laporan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Laporan harian dengan detail komprehensif
     */
    public function laporanHarian()
    {
        $hari = Carbon::today();

        // Statistik kendaraan
        $kendaraanMasuk = Kendaraan::whereDate('waktu_masuk', $hari)->count();
        $kendaraanKeluar = Kendaraan::whereDate('waktu_keluar', $hari)->count();

        // Pendapatan
        $pendapatanHarian = Kendaraan::whereDate('waktu_keluar', $hari)->sum('biaya_parkir');

        // Breakdown berdasarkan jenis kendaraan
        $detailKendaraan = Kendaraan::whereDate('waktu_masuk', $hari)
            ->select('jenis_kendaraan', 
                DB::raw('COUNT(*) as total_kendaraan'),
                DB::raw('SUM(biaya_parkir) as total_pendapatan')
            )
            ->groupBy('jenis_kendaraan')
            ->get();

        // Simpan laporan
        $laporan = Laporan::create([
            'tanggal' => $hari,
            'jenis_laporan' => 'harian',
            'total_kendaraan' => $kendaraanMasuk,
            'total_pendapatan' => $pendapatanHarian,
            'detail_laporan' => json_encode($detailKendaraan)
        ]);

        return response()->json([
            'laporan' => $laporan,
            'kendaraan_masuk' => $kendaraanMasuk,
            'kendaraan_keluar' => $kendaraanKeluar,
            'pendapatan_harian' => $pendapatanHarian,
            'detail_kendaraan' => $detailKendaraan
        ]);
    }

    /**
     * Laporan bulanan dengan analisis mendalam
     */
    public function laporanBulanan()
    {
        $bulan = Carbon::now()->startOfMonth();

        // Total kendaraan per bulan
        $kendaraanBulanan = Kendaraan::whereMonth('waktu_masuk', $bulan->month)
            ->whereYear('waktu_masuk', $bulan->year)
            ->count();

        // Pendapatan bulanan
        $pendapatanBulanan = Kendaraan::whereMonth('waktu_keluar', $bulan->month)
            ->whereYear('waktu_keluar', $bulan->year)
            ->sum('biaya_parkir');

        // Analisis berdasarkan jenis kendaraan
        $detailBulanan = Kendaraan::whereMonth('waktu_masuk', $bulan->month)
            ->whereYear('waktu_masuk', $bulan->year)
            ->select('jenis_kendaraan', 
                DB::raw('COUNT(*) as total_kendaraan'),
                DB::raw('SUM(biaya_parkir) as total_pendapatan'),
                DB::raw('AVG(durasi_parkir) as durasi_rata_rata')
            )
            ->groupBy('jenis_kendaraan')
            ->get();

        // Simpan laporan bulanan
        $laporan = Laporan::create([
            'tanggal' => $bulan,
            'jenis_laporan' => 'bulanan',
            'total_kendaraan' => $kendaraanBulanan,
            'total_pendapatan' => $pendapatanBulanan,
            'detail_laporan' => json_encode($detailBulanan)
        ]);

        return response()->json([
            'laporan' => $laporan,
            'total_kendaraan' => $kendaraanBulanan,
            'pendapatan_bulanan' => $pendapatanBulanan,
            'detail_bulanan' => $detailBulanan
        ]);
    }

    /**
     * Laporan pendapatan dengan proyeksi
     */
    public function laporanPendapatan()
    {
        // Pendapatan total
        $pendapatanTotal = Kendaraan::where('status', 'keluar')->sum('biaya_parkir');

        // Pendapatan per bulan
        $pendapatanPerBulan = Kendaraan::select(
            DB::raw('MONTH(waktu_keluar) as bulan'),
            DB::raw('SUM(biaya_parkir) as total_pendapatan')
        )
        ->whereNotNull('waktu_keluar')
        ->groupBy(DB::raw('MONTH(waktu_keluar)'))
        ->orderBy('bulan')
        ->get();

        // Proyeksi pendapatan
        $proyeksiPendapatan = $this->proyeksiPendapatanTahunan($pendapatanPerBulan);

        return response()->json([
            'pendapatan_total' => $pendapatanTotal,
            'pendapatan_per_bulan' => $pendapatanPerBulan,
            'proyeksi_pendapatan' => $proyeksiPendapatan
        ]);
    }

    /**
     * Proyeksi pendapatan tahunan
     */
    private function proyeksiPendapatanTahunan($pendapatanPerBulan)
    {
        // Hitung rata-rata pendapatan per bulan
        $totalBulan = $pendapatanPerBulan->count();
        $totalPendapatan = $pendapatanPerBulan->sum('total_pendapatan');
        
        $rataRataPendapatan = $totalPendapatan / max(1, $totalBulan);
        
        // Proyeksi untuk sisa bulan
        $sisaBulan = 12 - $totalBulan;
        $proyeksiTahunan = $totalPendapatan + ($rataRataPendapatan * $sisaBulan);

        return [
            'total_pendapatan_sekarang' => $totalPendapatan,
            'rata_rata_pendapatan_bulanan' => $rataRataPendapatan,
            'proyeksi_pendapatan_tahunan' => $proyeksiTahunan
        ];
    }

    /**
     * Daftar laporan dengan filter
     */
    public function daftarLaporan(Request $request)
    {
        $query = Laporan::query();

        // Filter berdasarkan jenis laporan
        if ($request->has('jenis')) {
            $query->where('jenis_laporan', $request->input('jenis'));
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has(['dari_tanggal', 'sampai_tanggal'])) {
            $query->whereBetween('tanggal', [
                Carbon::parse($request->dari_tanggal), 
                Carbon::parse($request->sampai_tanggal)
            ]);
        }

        // Urutkan dari yang terbaru
        $laporan = $query->orderBy('tanggal', 'desc')->paginate(10);

        return response()->json($laporan);
    }
}
