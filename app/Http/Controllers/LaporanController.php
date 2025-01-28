<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Pembayaran;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function daftarLaporan()
    {
        $pendapatanBulanan = $this->getPendapatanBulanan();
        $okupasiHarian = $this->getOkupasiHarian();
        $jenisKendaraan = $this->getJenisKendaraan();
        $waktuPuncak = $this->getWaktuPuncak();

        return view('laporan.index', compact(
            'pendapatanBulanan', 
            'okupasiHarian', 
            'jenisKendaraan', 
            'waktuPuncak'
        ));
    }

    public function filter(Request $request)
    {
        $filter = $request->input('filter', 'harian');

        switch ($filter) {
            case 'harian':
                $pendapatanBulanan = $this->getPendapatanHarian();
                break;
            case 'mingguan':
                $pendapatanBulanan = $this->getPendapatanMingguan();
                break;
            case 'bulanan':
                $pendapatanBulanan = $this->getPendapatanBulanan();
                break;
            default:
                $pendapatanBulanan = $this->getPendapatanHarian();
        }

        return response()->json($pendapatanBulanan);
    }

    private function getPendapatanHarian()
    {
        return Pembayaran::select(
            \DB::raw('DATE(created_at) as tanggal'),
            \DB::raw('SUM(total_bayar) as total_pendapatan')
        )
        ->whereDate('created_at', '>=', now()->subDays(7))
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->pluck('total_pendapatan', 'tanggal')
        ->toArray();
    }

    private function getPendapatanMingguan()
    {
        return Pembayaran::select(
            \DB::raw('YEARWEEK(created_at) as minggu'),
            \DB::raw('SUM(total_bayar) as total_pendapatan')
        )
        ->whereDate('created_at', '>=', now()->subMonths(3))
        ->groupBy('minggu')
        ->orderBy('minggu')
        ->pluck('total_pendapatan', 'minggu')
        ->toArray();
    }

    private function getPendapatanBulanan()
    {
        return Pembayaran::select(
            \DB::raw('MONTH(created_at) as bulan'),
            \DB::raw('SUM(total_bayar) as total_pendapatan')
        )
        ->whereDate('created_at', '>=', now()->subMonths(12))
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total_pendapatan', 'bulan')
        ->toArray();
    }

    private function getOkupasiHarian()
    {
        $totalSlot = 50; // Sesuaikan dengan jumlah slot parkir

        return Kendaraan::select(
            \DB::raw('DATE(waktu_masuk) as tanggal'),
            \DB::raw('(COUNT(*) / ' . $totalSlot . ' * 100) as persentase_okupasi')
        )
        ->whereDate('waktu_masuk', '>=', now()->subDays(7))
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->pluck('persentase_okupasi', 'tanggal')
        ->toArray();
    }

    private function getJenisKendaraan()
    {
        return Kendaraan::select(
            'jenis_kendaraan',
            \DB::raw('COUNT(*) as total')
        )
        ->whereDate('waktu_masuk', '>=', now()->subDays(30))
        ->groupBy('jenis_kendaraan')
        ->pluck('total', 'jenis_kendaraan')
        ->toArray();
    }

    private function getWaktuPuncak()
    {
        return Kendaraan::select(
            \DB::raw('HOUR(waktu_masuk) as jam'),
            \DB::raw('COUNT(*) as total_kendaraan')
        )
        ->whereDate('waktu_masuk', '>=', now()->subDays(30))
        ->groupBy('jam')
        ->orderBy('total_kendaraan', 'desc')
        ->limit(5)
        ->pluck('total_kendaraan', 'jam')
        ->toArray();
    }

    public function laporanBulanan()
    {
        $laporanBulanan = Pembayaran::select(
            \DB::raw('MONTH(created_at) as bulan'),
            \DB::raw('SUM(total_bayar) as total_pendapatan'),
            \DB::raw('COUNT(*) as total_transaksi')
        )
        ->groupBy('bulan')
        ->get();

        return view('laporan.bulanan', compact('laporanBulanan'));
    }

    public function laporanPendapatan()
    {
        $pendapatanPerKategori = Pembayaran::select(
            'jenis_kendaraan',
            \DB::raw('SUM(total_bayar) as total_pendapatan')
        )
        ->groupBy('jenis_kendaraan')
        ->get();

        return view('laporan.pendapatan', compact('pendapatanPerKategori'));
    }
}
