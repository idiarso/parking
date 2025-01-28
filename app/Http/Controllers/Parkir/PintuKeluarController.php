<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use App\Services\PembayaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class PintuKeluarController extends Controller
{
    protected $pembayaranService;

    public function __construct(PembayaranService $pembayaranService)
    {
        $this->pembayaranService = $pembayaranService;
    }

    public function index()
    {
        return view('pintu-keluar.index');
    }

    public function verifikasiKendaraan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plat_nomor' => [
                'required', 
                'regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/',
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kendaraan = Kendaraan::where('plat_nomor', $request->plat_nomor)
            ->where('status', 'parkir')
            ->first();

        if (!$kendaraan) {
            return redirect()->back()
                ->with('error', 'Kendaraan tidak ditemukan atau sudah keluar')
                ->withInput();
        }

        // Dapatkan tarif aktif
        $tarif = TarifParkir::where('jenis_kendaraan', $kendaraan->jenis_kendaraan)
            ->where('aktif', true)
            ->firstOrFail();

        // Hitung durasi dan biaya parkir
        $kendaraan->waktu_keluar = now();
        $kendaraan->durasi_parkir = $this->hitungDurasiParkir($kendaraan->waktu_masuk, $kendaraan->waktu_keluar);
        $kendaraan->biaya_parkir = $this->hitungBiayaParkir($kendaraan->durasi_parkir, $tarif);

        return view('pintu-keluar.konfirmasi', compact('kendaraan', 'tarif'));
    }

    public function prosesPembayaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'metode_pembayaran' => 'required|in:tunai,transfer,qris'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
            $tarif = TarifParkir::where('jenis_kendaraan', $kendaraan->jenis_kendaraan)
                ->where('aktif', true)
                ->firstOrFail();

            // Proses pembayaran
            $pembayaran = $this->pembayaranService->prosesPembayaran(
                $kendaraan, 
                $tarif, 
                $request->metode_pembayaran
            );

            // Update status kendaraan
            $kendaraan->update([
                'status' => 'keluar',
                'metode_pembayaran' => $request->metode_pembayaran
            ]);

            // Bebaskan slot parkir
            SlotParkir::where('kendaraan_id', $kendaraan->id)
                ->update([
                    'status' => 'kosong', 
                    'kendaraan_id' => null
                ]);

            DB::commit();

            // Generate kwitansi
            $pdf = PDF::loadView('kwitansi.parkir', compact('kendaraan', 'pembayaran', 'tarif'));
            return $pdf->download("kwitansi_parkir_{$kendaraan->plat_nomor}.pdf");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    private function hitungDurasiParkir($waktuMasuk, $waktuKeluar)
    {
        $durasi = $waktuMasuk->diffInHours($waktuKeluar, false);
        return max(1, ceil($durasi)); // Minimal 1 jam
    }

    private function hitungBiayaParkir($durasi, $tarif)
    {
        return $durasi * $tarif->tarif_per_jam;
    }
}
