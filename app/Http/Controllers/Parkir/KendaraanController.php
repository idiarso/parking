<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kendaraan::query();

        // Filter berdasarkan plat nomor
        if ($request->filled('plat_nomor')) {
            $query->cariPlatNomor($request->plat_nomor);
        }

        // Filter berdasarkan jenis kendaraan
        if ($request->filled('jenis_kendaraan')) {
            $query->jenisKendaraan($request->jenis_kendaraan);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Filter berdasarkan rentang waktu
        if ($request->filled('mulai') && $request->filled('selesai')) {
            $query->rentangWaktu(
                Carbon::parse($request->mulai), 
                Carbon::parse($request->selesai)
            );
        }

        $kendaraan = $query->orderBy('waktu_masuk', 'desc')->paginate(10);

        return view('kendaraan.index', compact('kendaraan'));
    }

    public function create()
    {
        $slotTersedia = SlotParkir::where('status', 'kosong')->get();
        return view('kendaraan.create', compact('slotTersedia'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plat_nomor' => [
                'required', 
                'regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/',
                Rule::unique('kendaraan')->where(function ($query) {
                    return $query->where('status', 'parkir');
                })
            ],
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'slot_parkir_id' => 'required|exists:slot_parkir,id',
            'catatan' => 'nullable|string|max:255'
        ], [
            'plat_nomor.unique' => 'Kendaraan dengan plat nomor ini sudah ada di area parkir.',
            'plat_nomor.regex' => 'Format plat nomor tidak valid.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek ketersediaan slot
        $slotParkir = SlotParkir::findOrFail($request->slot_parkir_id);
        if ($slotParkir->status !== 'kosong') {
            return redirect()->back()
                ->with('error', 'Slot parkir sudah terisi.')
                ->withInput();
        }

        // Dapatkan tarif aktif
        $tarif = TarifParkir::where('jenis_kendaraan', $request->jenis_kendaraan)
            ->where('aktif', true)
            ->first();

        if (!$tarif) {
            return redirect()->back()
                ->with('error', 'Tarif parkir untuk jenis kendaraan ini belum dikonfigurasi.')
                ->withInput();
        }

        // Buat entri kendaraan
        $kendaraan = Kendaraan::create([
            'plat_nomor' => $request->plat_nomor,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'waktu_masuk' => now(),
            'status' => 'parkir',
            'catatan' => $request->catatan
        ]);

        // Update status slot parkir
        $slotParkir->update([
            'status' => 'terisi',
            'kendaraan_id' => $kendaraan->id
        ]);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil didaftarkan');
    }

    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $slotTersedia = SlotParkir::where('status', 'kosong')->get();

        return view('kendaraan.edit', compact('kendaraan', 'slotTersedia'));
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'plat_nomor' => [
                'required', 
                'regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/',
                Rule::unique('kendaraan')->ignore($id)->where(function ($query) {
                    return $query->where('status', 'parkir');
                })
            ],
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'slot_parkir_id' => 'required|exists:slot_parkir,id',
            'catatan' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update data kendaraan
        $kendaraan->update([
            'plat_nomor' => $request->plat_nomor,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        // Nonaktifkan slot parkir terkait
        SlotParkir::where('kendaraan_id', $kendaraan->id)
            ->update([
                'status' => 'kosong', 
                'kendaraan_id' => null
            ]);

        $kendaraan->delete();

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil dihapus');
    }

    public function riwayat()
    {
        $riwayatKendaraan = Kendaraan::withTrashed()
            ->orderBy('waktu_masuk', 'desc')
            ->paginate(50);

        return view('kendaraan.riwayat', compact('riwayatKendaraan'));
    }
}
