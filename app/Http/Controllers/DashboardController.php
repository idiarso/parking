<?php

namespace App\Http\Controllers;

use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use App\Models\Parkir\Laporan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik harian
        $hariIni = Carbon::today();
        $kendaraanHariIni = Kendaraan::whereDate('waktu_masuk', $hariIni)->count();
        $pendapatanHariIni = Kendaraan::whereDate('waktu_keluar', $hariIni)->sum('biaya_parkir');

        // Status slot parkir
        $slotMotor = SlotParkir::where('jenis_kendaraan', 'motor')->count();
        $slotMotorTerisi = SlotParkir::where('jenis_kendaraan', 'motor')->where('status', 'terisi')->count();
        $slotMobil = SlotParkir::where('jenis_kendaraan', 'mobil')->count();
        $slotMobilTerisi = SlotParkir::where('jenis_kendaraan', 'mobil')->where('status', 'terisi')->count();

        // Tarif parkir
        $tarifMotor = TarifParkir::where('jenis_kendaraan', 'motor')->first();
        $tarifMobil = TarifParkir::where('jenis_kendaraan', 'mobil')->first();

        // Laporan terakhir
        $laporanTerakhir = Laporan::latest()->first();

        return view('dashboard', [
            'kendaraan_hari_ini' => $kendaraanHariIni,
            'pendapatan_hari_ini' => $pendapatanHariIni,
            'slot_motor_total' => $slotMotor,
            'slot_motor_terisi' => $slotMotorTerisi,
            'slot_mobil_total' => $slotMobil,
            'slot_mobil_terisi' => $slotMobilTerisi,
            'tarif_motor' => $tarifMotor,
            'tarif_mobil' => $tarifMobil,
            'laporan_terakhir' => $laporanTerakhir
        ]);
    }
}
