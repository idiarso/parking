<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function simulasiMasuk($jenisKendaraan = 'motor')
    {
        // Cari slot parkir kosong untuk jenis kendaraan tertentu
        $slotParkir = SlotParkir::where('jenis_kendaraan', $jenisKendaraan)
            ->where('status', 'kosong')
            ->first();

        if (!$slotParkir) {
            return response()->json([
                'error' => "Tidak ada slot parkir {$jenisKendaraan} tersedia"
            ], 400);
        }

        // Generate plat nomor acak
        $platNomor = $this->generatePlatNomor($jenisKendaraan);

        // Buat entri kendaraan
        $kendaraan = Kendaraan::create([
            'plat_nomor' => $platNomor,
            'jenis_kendaraan' => $jenisKendaraan,
            'waktu_masuk' => now(),
            'status' => 'parkir',
            'catatan' => 'Kendaraan masuk melalui simulasi'
        ]);

        // Update slot parkir
        $slotParkir->update([
            'status' => 'terisi',
            'kendaraan_id' => $kendaraan->id
        ]);

        return response()->json([
            'kendaraan' => $kendaraan,
            'slot_parkir' => $slotParkir,
            'message' => 'Kendaraan berhasil masuk'
        ]);
    }

    public function simulasiKeluarSemua()
    {
        // Ambil semua kendaraan yang sedang parkir
        $kendaraanParkir = Kendaraan::where('status', 'parkir')->get();
        $hasilKeluaran = [];

        foreach ($kendaraanParkir as $kendaraan) {
            $hasilKeluaran[] = $this->simulasiKeluar($kendaraan->id);
        }

        return response()->json([
            'total_kendaraan_keluar' => count($hasilKeluaran),
            'detail' => $hasilKeluaran
        ]);
    }

    public function simulasiKeluar($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        
        // Dapatkan tarif
        $tarif = TarifParkir::where('jenis_kendaraan', $kendaraan->jenis_kendaraan)->first();
        
        // Hitung durasi dan biaya (minimal 1 jam)
        $kendaraan->waktu_keluar = now();
        $durasi = max(1, now()->diffInHours($kendaraan->waktu_masuk));
        $biaya = $durasi * $tarif->tarif_per_jam;
        
        $kendaraan->durasi_parkir = $durasi;
        $kendaraan->biaya_parkir = $biaya;
        $kendaraan->status = 'keluar';
        $kendaraan->catatan = 'Kendaraan keluar melalui simulasi';
        $kendaraan->save();

        // Update slot parkir
        $slotParkir = SlotParkir::where('kendaraan_id', $kendaraan->id)->first();
        if ($slotParkir) {
            $slotParkir->update([
                'status' => 'kosong',
                'kendaraan_id' => null
            ]);
        }

        return [
            'kendaraan' => $kendaraan,
            'durasi' => $durasi,
            'biaya' => $biaya,
            'message' => 'Kendaraan berhasil keluar'
        ];
    }

    private function generatePlatNomor($jenisKendaraan)
    {
        $kodeWilayah = ['B', 'D', 'F', 'H', 'K'];
        $nomorAcak = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $kodeHuruf = Str::random(3);

        return $kodeWilayah[array_rand($kodeWilayah)] . ' ' . $nomorAcak . ' ' . strtoupper($kodeHuruf);
    }
}
