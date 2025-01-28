<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use App\Models\Parkir\Laporan;
use Carbon\Carbon;

class MenuController extends Controller
{
    public function dashboard()
    {
        // Statistik Harian
        $hariIni = Carbon::today();
        $kendaraanHariIni = Kendaraan::whereDate('waktu_masuk', $hariIni)->count();
        $pendapatanHariIni = Kendaraan::whereDate('waktu_keluar', $hariIni)->sum('biaya_parkir');
        
        // Status Slot
        $totalSlot = SlotParkir::count();
        $slotTerisi = SlotParkir::where('status', 'terisi')->count();
        $slotKosong = $totalSlot - $slotTerisi;

        // Grafik Okupansi
        $okupasiPerJam = Kendaraan::select(
            \DB::raw('HOUR(waktu_masuk) as jam'),
            \DB::raw('COUNT(*) as total_kendaraan')
        )
        ->groupBy('jam')
        ->orderBy('jam')
        ->get();

        // Jika tidak ada data, tambahkan data dummy
        if ($okupasiPerJam->isEmpty()) {
            \Log::warning('Tidak ada data okupasi per jam, menggunakan data dummy');
            $okupasiPerJam = [
                ['jam' => 0, 'total_kendaraan' => 0],
                ['jam' => 6, 'total_kendaraan' => 5],
                ['jam' => 12, 'total_kendaraan' => 15],
                ['jam' => 18, 'total_kendaraan' => 10],
                ['jam' => 23, 'total_kendaraan' => 3]
            ];
        } else {
            // Konversi ke array jika masih dalam bentuk Collection
            $okupasiPerJam = $okupasiPerJam->toArray();
        }

        // Log untuk debug
        \Log::info('Okupasi Per Jam:', $okupasiPerJam);

        return view('dashboard', [
            'kendaraanHariIni' => $kendaraanHariIni, 
            'pendapatanHariIni' => $pendapatanHariIni, 
            'totalSlot' => $totalSlot, 
            'slotTerisi' => $slotTerisi, 
            'slotKosong' => $slotKosong,
            'okupasiPerJam' => $okupasiPerJam
        ]);
    }

    public function manajemenKendaraan()
    {
        $kendaraan = Kendaraan::paginate(10);
        return view('kendaraan.index', compact('kendaraan'));
    }

    public function sistemParkir()
    {
        // Ambil data slot parkir
        $slotParkir = SlotParkir::with('kendaraan')->get();
        
        // Ambil tarif parkir
        $tarifParkir = TarifParkir::all();

        return view('menu.parkir.sistem', compact('slotParkir', 'tarifParkir'));
    }

    public function pintuMasuk()
    {
        $slotKosong = SlotParkir::where('status', 'kosong')->get();
        return view('parkir.masuk', compact('slotKosong'));
    }

    public function pintuKeluar()
    {
        $kendaraanDiparkir = Kendaraan::where('status', 'parkir')->get();
        return view('parkir.keluar', compact('kendaraanDiparkir'));
    }

    public function laporan()
    {
        $laporanHarian = Laporan::where('jenis_laporan', 'harian')->latest()->paginate(10);
        $laporanBulanan = Laporan::where('jenis_laporan', 'bulanan')->latest()->paginate(10);
        return view('laporan.index', compact('laporanHarian', 'laporanBulanan'));
    }

    public function pengaturan()
    {
        // Ambil tarif parkir
        $tarifParkir = TarifParkir::all();
        
        // Ambil data pengguna
        $pengguna = User::all();

        return view('menu.pengaturan.index', compact('tarifParkir', 'pengguna'));
    }

    public function keamananSistem()
    {
        // Ambil log aktivitas
        $logAktivitas = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('menu.keamanan.index', compact('logAktivitas'));
    }
}
