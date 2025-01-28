<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function masukKendaraan(Request $request)
    {
        $validatedData = $request->validate([
            'plat_nomor' => 'required|unique:kendaraan,plat_nomor',
            'jenis_kendaraan' => 'required|in:motor,mobil'
        ]);

        // Cari slot parkir kosong
        $slotParkir = SlotParkir::cariSlotKosong($validatedData['jenis_kendaraan']);

        if (!$slotParkir) {
            return response()->json([
                'message' => 'Tidak ada slot parkir tersedia untuk jenis kendaraan ini'
            ], 400);
        }

        // Buat entri kendaraan
        $kendaraan = Kendaraan::masuk(
            $validatedData['plat_nomor'], 
            $validatedData['jenis_kendaraan']
        );

        // Update slot parkir
        $slotParkir->aturStatus('terisi', $kendaraan->id);

        return response()->json([
            'message' => 'Kendaraan berhasil masuk',
            'kendaraan' => $kendaraan,
            'slot_parkir' => $slotParkir
        ]);
    }

    public function keluarKendaraan(Request $request)
    {
        $validatedData = $request->validate([
            'plat_nomor' => 'required|exists:kendaraan,plat_nomor'
        ]);

        // Cari kendaraan yang sedang parkir
        $kendaraan = Kendaraan::where('plat_nomor', $validatedData['plat_nomor'])
            ->sedangParkir()
            ->first();

        if (!$kendaraan) {
            return response()->json([
                'message' => 'Kendaraan tidak ditemukan atau sudah keluar'
            ], 404);
        }

        // Dapatkan tarif sesuai jenis kendaraan
        $tarif = TarifParkir::getTarifByJenisKendaraan($kendaraan->jenis_kendaraan);

        if (!$tarif) {
            return response()->json([
                'message' => 'Tarif parkir tidak ditemukan'
            ], 400);
        }

        // Proses keluar kendaraan
        $kendaraan = $kendaraan->keluar($tarif->tarif_per_jam);

        // Update slot parkir
        $slotParkir = $kendaraan->slotParkir;
        $slotParkir->aturStatus('kosong');

        return response()->json([
            'message' => 'Kendaraan berhasil keluar',
            'kendaraan' => $kendaraan,
            'biaya' => $kendaraan->biaya_parkir
        ]);
    }

    public function daftarKendaraan()
    {
        $kendaraan = Kendaraan::all();
        return response()->json($kendaraan);
    }

    public function detailKendaraan($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        return response()->json($kendaraan);
    }

    public function hapusKendaraan($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return response()->json([
            'message' => 'Kendaraan berhasil dihapus'
        ]);
    }
}
