<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SlotParkir;
use App\Models\Kendaraan;
use App\Models\Pembayaran;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function index()
    {
        // Ambil semua slot parkir dengan relasi kendaraan
        $slotParkir = SlotParkir::with('kendaraan')->get();

        // Status terkini
        $statusTerkini = [
            'total_slot' => SlotParkir::count(),
            'slot_terisi' => SlotParkir::where('status', 'terisi')->count(),
            'slot_kosong' => SlotParkir::where('status', 'kosong')->count(),
            'pendapatan_hari_ini' => Pembayaran::whereDate('created_at', today())->sum('total_bayar'),
            'kendaraan_masuk' => Kendaraan::whereDate('waktu_masuk', today())->count()
        ];

        // Riwayat transaksi terbaru
        $riwayatTransaksi = Kendaraan::with('pembayaran')
            ->orderBy('waktu_masuk', 'desc')
            ->limit(10)
            ->get();

        return view('monitoring.index', compact('slotParkir', 'statusTerkini', 'riwayatTransaksi'));
    }

    public function getRealtimeData()
    {
        $slotParkir = SlotParkir::with('kendaraan')->get();
        $statusTerkini = [
            'total_slot' => SlotParkir::count(),
            'slot_terisi' => SlotParkir::where('status', 'terisi')->count(),
            'slot_kosong' => SlotParkir::where('status', 'kosong')->count(),
            'pendapatan_hari_ini' => Pembayaran::whereDate('created_at', today())->sum('total_bayar'),
            'kendaraan_masuk' => Kendaraan::whereDate('waktu_masuk', today())->count()
        ];

        $riwayatTransaksi = Kendaraan::with('pembayaran')
            ->orderBy('waktu_masuk', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'slotParkir' => $slotParkir,
            'statusTerkini' => $statusTerkini,
            'riwayatTransaksi' => $riwayatTransaksi
        ]);
    }
}
