<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\TarifParkir;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function laporanHarian(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        
        $laporanHarian = Kendaraan::whereDate('waktu_masuk', $tanggal)
            ->orWhereDate('waktu_keluar', $tanggal)
            ->get();

        $totalPendapatan = $laporanHarian->sum('biaya_parkir');
        $totalKendaraan = $laporanHarian->count();
        $kendaraanPerJenis = $laporanHarian->groupBy('jenis_kendaraan')
            ->map(function ($group) {
                return $group->count();
            });

        return view('laporan.harian', [
            'laporanHarian' => $laporanHarian,
            'tanggal' => $tanggal,
            'totalPendapatan' => $totalPendapatan,
            'totalKendaraan' => $totalKendaraan,
            'kendaraanPerJenis' => $kendaraanPerJenis
        ]);
    }

    public function cetakLaporanHarian(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        
        $laporanHarian = Kendaraan::whereDate('waktu_masuk', $tanggal)
            ->orWhereDate('waktu_keluar', $tanggal)
            ->get();

        $totalPendapatan = $laporanHarian->sum('biaya_parkir');
        $totalKendaraan = $laporanHarian->count();
        $kendaraanPerJenis = $laporanHarian->groupBy('jenis_kendaraan')
            ->map(function ($group) {
                return $group->count();
            });

        $pdf = PDF::loadView('laporan.cetak-harian', [
            'laporanHarian' => $laporanHarian,
            'tanggal' => $tanggal,
            'totalPendapatan' => $totalPendapatan,
            'totalKendaraan' => $totalKendaraan,
            'kendaraanPerJenis' => $kendaraanPerJenis
        ]);

        return $pdf->download("laporan_parkir_harian_{$tanggal}.pdf");
    }

    public function laporanBulanan(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        
        $laporanBulanan = Kendaraan::whereRaw("DATE_FORMAT(waktu_masuk, '%Y-%m') = ?", [$bulan])
            ->orWhereRaw("DATE_FORMAT(waktu_keluar, '%Y-%m') = ?", [$bulan])
            ->get();

        $totalPendapatan = $laporanBulanan->sum('biaya_parkir');
        $totalKendaraan = $laporanBulanan->count();
        $kendaraanPerJenis = $laporanBulanan->groupBy('jenis_kendaraan')
            ->map(function ($group) {
                return $group->count();
            });

        $pendapatanHarian = $laporanBulanan->groupBy(function($item) {
            return Carbon::parse($item->waktu_masuk)->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('biaya_parkir');
        });

        return view('laporan.bulanan', [
            'laporanBulanan' => $laporanBulanan,
            'bulan' => $bulan,
            'totalPendapatan' => $totalPendapatan,
            'totalKendaraan' => $totalKendaraan,
            'kendaraanPerJenis' => $kendaraanPerJenis,
            'pendapatanHarian' => $pendapatanHarian
        ]);
    }

    public function cetakLaporanBulanan(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        
        $laporanBulanan = Kendaraan::whereRaw("DATE_FORMAT(waktu_masuk, '%Y-%m') = ?", [$bulan])
            ->orWhereRaw("DATE_FORMAT(waktu_keluar, '%Y-%m') = ?", [$bulan])
            ->get();

        $totalPendapatan = $laporanBulanan->sum('biaya_parkir');
        $totalKendaraan = $laporanBulanan->count();
        $kendaraanPerJenis = $laporanBulanan->groupBy('jenis_kendaraan')
            ->map(function ($group) {
                return $group->count();
            });

        $pendapatanHarian = $laporanBulanan->groupBy(function($item) {
            return Carbon::parse($item->waktu_masuk)->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('biaya_parkir');
        });

        $pdf = PDF::loadView('laporan.cetak-bulanan', [
            'laporanBulanan' => $laporanBulanan,
            'bulan' => $bulan,
            'totalPendapatan' => $totalPendapatan,
            'totalKendaraan' => $totalKendaraan,
            'kendaraanPerJenis' => $kendaraanPerJenis,
            'pendapatanHarian' => $pendapatanHarian
        ]);

        return $pdf->download("laporan_parkir_bulanan_{$bulan}.pdf");
    }
}
