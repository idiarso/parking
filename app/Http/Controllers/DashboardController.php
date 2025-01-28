<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Kendaraan Hari Ini
        $today = Carbon::today();
        $kendaraanHariIni = Kendaraan::whereDate('waktu_masuk', $today)->get();
        
        $totalKendaraan = $kendaraanHariIni->count();
        $kendaraanPerJenis = $kendaraanHariIni->groupBy('jenis_kendaraan')
            ->map->count();

        // Statistik Slot Parkir
        $totalSlot = SlotParkir::count();
        $slotTerisi = SlotParkir::where('status', 'terisi')->count();
        $slotKosong = $totalSlot - $slotTerisi;

        // Pendapatan Harian
        $pendapatanHarian = $kendaraanHariIni->sum('biaya_parkir');

        // Grafik Pendapatan Mingguan
        $pendapatanMingguan = $this->getPendapatanMingguan();

        // Grafik Okupansi Parkir
        $okupasiParkir = $this->getOkupasiParkir();

        return view('dashboard.index', [
            'totalKendaraan' => $totalKendaraan,
            'kendaraanPerJenis' => $kendaraanPerJenis,
            'totalSlot' => $totalSlot,
            'slotTerisi' => $slotTerisi,
            'slotKosong' => $slotKosong,
            'pendapatanHarian' => $pendapatanHarian,
            'pendapatanMingguan' => $pendapatanMingguan,
            'okupasiParkir' => $okupasiParkir
        ]);
    }

    private function getPendapatanMingguan()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return Kendaraan::select(
            DB::raw('DATE(waktu_masuk) as tanggal'),
            DB::raw('SUM(biaya_parkir) as total_pendapatan')
        )
        ->whereBetween('waktu_masuk', [$startOfWeek, $endOfWeek])
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get()
        ->pluck('total_pendapatan', 'tanggal');
    }

    private function getOkupasiParkir()
    {
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        return Kendaraan::select(
            DB::raw('HOUR(waktu_masuk) as jam'),
            DB::raw('COUNT(*) as jumlah_kendaraan')
        )
        ->whereBetween('waktu_masuk', [$startOfDay, $endOfDay])
        ->groupBy('jam')
        ->orderBy('jam')
        ->get()
        ->pluck('jumlah_kendaraan', 'jam');
    }
}
