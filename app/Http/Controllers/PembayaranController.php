<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaranTerakhir = Pembayaran::with('kendaraan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $statistikPembayaran = [
            'total_pendapatan' => Pembayaran::selesai()->sum('total_bayar'),
            'transaksi_hari_ini' => Pembayaran::whereDate('created_at', today())->count(),
            'rata_pendapatan' => Pembayaran::selesai()->avg('total_bayar')
        ];

        return view('pembayaran.index', compact('pembayaranTerakhir', 'statistikPembayaran'));
    }

    public function prosesKeluar(Request $request)
    {
        $validatedData = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'metode_pembayaran' => 'required|in:tunai,qris,transfer,e-wallet,kartu_parkir'
        ]);

        $kendaraan = Kendaraan::findOrFail($validatedData['kendaraan_id']);
        
        // Hitung waktu parkir
        $waktuMasuk = $kendaraan->waktu_masuk;
        $waktuKeluar = now();

        // Hitung tarif
        $tarifDetail = Pembayaran::hitungTarif($kendaraan, $waktuMasuk, $waktuKeluar);

        // Buat pembayaran
        $pembayaran = Pembayaran::create([
            'kendaraan_id' => $kendaraan->id,
            'total_bayar' => $tarifDetail['total_bayar'],
            'metode_pembayaran' => $validatedData['metode_pembayaran'],
            'status_pembayaran' => 'selesai',
            'waktu_masuk' => $waktuMasuk,
            'waktu_keluar' => $waktuKeluar,
            'durasi_parkir' => $tarifDetail['durasi'],
            'potongan_harga' => $tarifDetail['total_bayar'] * ($tarifDetail['diskon'] / 100),
            'kode_transaksi' => Pembayaran::generateKodeTransaksi()
        ]);

        // Update status kendaraan
        $kendaraan->update([
            'status' => 'keluar',
            'waktu_keluar' => $waktuKeluar
        ]);

        return response()->json([
            'status' => 'success',
            'pembayaran' => $pembayaran,
            'tarif_detail' => $tarifDetail
        ]);
    }

    public function cetakStruk($pembayaranId)
    {
        $pembayaran = Pembayaran::with('kendaraan')->findOrFail($pembayaranId);

        // Logika cetak struk
        // Bisa menggunakan library PDF atau printer thermal
        return view('pembayaran.struk', compact('pembayaran'));
    }

    public function metodePembayaran()
    {
        return response()->json([
            'metode' => [
                [
                    'kode' => 'tunai',
                    'nama' => 'Tunai',
                    'icon' => 'fas fa-money-bill-wave'
                ],
                [
                    'kode' => 'qris',
                    'nama' => 'QRIS',
                    'icon' => 'fas fa-qrcode'
                ],
                [
                    'kode' => 'transfer',
                    'nama' => 'Transfer Bank',
                    'icon' => 'fas fa-university'
                ],
                [
                    'kode' => 'e-wallet',
                    'nama' => 'E-Wallet',
                    'icon' => 'fas fa-mobile-alt'
                ],
                [
                    'kode' => 'kartu_parkir',
                    'nama' => 'Kartu Parkir',
                    'icon' => 'fas fa-parking'
                ]
            ]
        ]);
    }

    public function laporan(Request $request)
    {
        $validatedData = $request->validate([
            'rentang' => 'required|in:harian,mingguan,bulanan,tahunan',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai'
        ]);

        $query = Pembayaran::selesai();

        switch ($validatedData['rentang']) {
            case 'harian':
                $query->whereDate('created_at', today());
                break;
            case 'mingguan':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'bulanan':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'tahunan':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $laporan = $query->select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(total_bayar) as total_pendapatan'),
            DB::raw('COUNT(*) as total_transaksi')
        )
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'desc')
        ->get();

        return response()->json($laporan);
    }
}
