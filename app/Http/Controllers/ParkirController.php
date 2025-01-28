<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\SlotParkir;
use Carbon\Carbon;

class ParkirController extends Controller
{
    public function masuk()
    {
        $slotKosong = SlotParkir::where('status', 'kosong')->get();
        $riwayatMasuk = Kendaraan::whereDate('waktu_masuk', today())->get();

        return view('parkir.masuk', compact('slotKosong', 'riwayatMasuk'));
    }

    public function prosesMasuk(Request $request)
    {
        $validatedData = $request->validate([
            'plat_nomor' => 'required|string',
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'slot_parkir' => 'required|exists:slot_parkir,id',
            'kondisi_kendaraan' => 'nullable|string'
        ]);

        $slot = SlotParkir::findOrFail($validatedData['slot_parkir']);

        // Cek apakah slot masih kosong
        if ($slot->status !== 'kosong') {
            return back()->with('error', 'Slot parkir sudah terisi');
        }

        $kendaraan = Kendaraan::create([
            'plat_nomor' => $validatedData['plat_nomor'],
            'jenis_kendaraan' => $validatedData['jenis_kendaraan'],
            'slot_parkir_id' => $slot->id,
            'waktu_masuk' => now(),
            'kondisi_kendaraan' => $validatedData['kondisi_kendaraan'] ?? null
        ]);

        // Update status slot
        $slot->update(['status' => 'terisi']);

        return back()->with('success', 'Kendaraan berhasil masuk');
    }

    public function keluar()
    {
        $kendaraanDiparkir = Kendaraan::where('status', 'parkir')->get();
        $riwayatKeluar = Kendaraan::whereDate('waktu_keluar', today())->get();

        return view('parkir.keluar', compact('kendaraanDiparkir', 'riwayatKeluar'));
    }

    public function prosesKeluar(Request $request)
    {
        $validatedData = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id'
        ]);

        $kendaraan = Kendaraan::findOrFail($validatedData['kendaraan_id']);

        // Hitung durasi dan biaya parkir
        $waktuMasuk = Carbon::parse($kendaraan->waktu_masuk);
        $waktuKeluar = now();
        $durasiParkir = $waktuMasuk->diffInHours($waktuKeluar);

        // Hitung tarif berdasarkan jenis kendaraan
        $tarifPerjam = $kendaraan->jenis_kendaraan === 'motor' 
            ? config('parkir.tarif_motor') 
            : config('parkir.tarif_mobil');

        $totalBiaya = $this->hitungBiayaParkir($durasiParkir, $tarifPerjam);

        // Update data kendaraan
        $kendaraan->update([
            'waktu_keluar' => $waktuKeluar,
            'durasi_parkir' => $durasiParkir,
            'biaya_parkir' => $totalBiaya,
            'status' => 'keluar'
        ]);

        // Kembalikan status slot
        $slot = $kendaraan->slot;
        $slot->update(['status' => 'kosong']);

        return back()->with('success', 'Kendaraan berhasil keluar');
    }

    private function hitungBiayaParkir($durasi, $tarifPerjam)
    {
        // Logika perhitungan tarif parkir
        $jamPertama = $tarifPerjam['jam_pertama'];
        $jamSelanjutnya = $tarifPerjam['jam_selanjutnya'];

        $totalBiaya = $jamPertama;
        if ($durasi > 1) {
            $totalBiaya += ($durasi - 1) * $jamSelanjutnya;
        }

        return $totalBiaya;
    }
}
