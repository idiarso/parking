<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ParkirKeluarController extends Controller
{
    public function index()
    {
        // Ambil kendaraan yang sedang parkir
        $kendaraanParkir = Kendaraan::where('status', 'parkir')
            ->with('slotParkir')
            ->orderBy('waktu_masuk', 'asc')
            ->get();

        return view('parkir.keluar', [
            'kendaraanParkir' => $kendaraanParkir
        ]);
    }

    public function cariKendaraan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_plat' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $kendaraan = Kendaraan::where('nomor_plat', $request->nomor_plat)
            ->where('status', 'parkir')
            ->with('slotParkir')
            ->first();

        if (!$kendaraan) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan atau sudah keluar'
            ], 404);
        }

        // Hitung durasi parkir
        $waktuMasuk = Carbon::parse($kendaraan->waktu_masuk);
        $waktuSekarang = Carbon::now();
        $durasiParkir = $waktuMasuk->diffInMinutes($waktuSekarang);

        // Cari tarif parkir
        $tarifParkir = TarifParkir::where('jenis_kendaraan', $kendaraan->jenis_kendaraan)
            ->where('aktif', true)
            ->first();

        // Hitung biaya parkir
        $biayaParkir = 0;
        if ($tarifParkir) {
            $jamParkir = ceil($durasiParkir / 60);
            $biayaParkir = $jamParkir * $tarifParkir->tarif_per_jam;
        }

        return response()->json([
            'success' => true,
            'kendaraan' => $kendaraan,
            'durasi_parkir' => $durasiParkir,
            'biaya_parkir' => $biayaParkir
        ]);
    }

    public function prosesParkirKeluar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_plat' => 'required|string|max:20',
            'biaya_parkir' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cari kendaraan yang sedang parkir
            $kendaraan = Kendaraan::where('nomor_plat', $request->nomor_plat)
                ->where('status', 'parkir')
                ->first();

            if (!$kendaraan) {
                return redirect()->back()
                    ->with('error', 'Kendaraan tidak ditemukan atau sudah keluar')
                    ->withInput();
            }

            // Update slot parkir
            $slotParkir = $kendaraan->slotParkir;
            if ($slotParkir) {
                $slotParkir->update(['status' => 'tersedia']);
            }

            // Update kendaraan
            $kendaraan->update([
                'status' => 'keluar',
                'waktu_keluar' => Carbon::now(),
                'biaya_parkir' => $request->biaya_parkir
            ]);

            DB::commit();

            return redirect()->route('parkir.keluar')
                ->with('success', "Kendaraan dengan plat nomor {$kendaraan->nomor_plat} berhasil keluar. Biaya parkir: Rp " . number_format($request->biaya_parkir, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memproses parkir keluar: ' . $e->getMessage())
                ->withInput();
        }
    }
}
