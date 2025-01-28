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

class ParkirMasukController extends Controller
{
    public function index()
    {
        // Ambil slot parkir yang tersedia
        $slotMotor = SlotParkir::where('jenis_kendaraan', 'motor')
            ->where('status', 'tersedia')
            ->count();
        
        $slotMobil = SlotParkir::where('jenis_kendaraan', 'mobil')
            ->where('status', 'tersedia')
            ->count();

        return view('parkir.masuk', [
            'slot_motor' => $slotMotor,
            'slot_mobil' => $slotMobil
        ]);
    }

    public function prosesParkirMasuk(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nomor_plat' => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'pemilik' => 'nullable|string|max:100',
            'merk' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:30'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cari slot parkir yang tersedia
            $slotParkir = SlotParkir::where('jenis_kendaraan', $request->jenis_kendaraan)
                ->where('status', 'tersedia')
                ->first();

            if (!$slotParkir) {
                return redirect()->back()
                    ->with('error', 'Maaf, slot parkir untuk ' . $request->jenis_kendaraan . ' sedang penuh.');
            }

            // Update status slot parkir
            $slotParkir->update(['status' => 'terisi']);

            // Buat entri kendaraan
            $kendaraan = Kendaraan::create([
                'nomor_plat' => $request->nomor_plat,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'slot_parkir_id' => $slotParkir->id,
                'waktu_masuk' => Carbon::now(),
                'status' => 'parkir',
                'pemilik' => $request->pemilik,
                'merk' => $request->merk,
                'warna' => $request->warna
            ]);

            DB::commit();

            return redirect()->route('parkir.masuk')
                ->with('success', 'Kendaraan berhasil masuk. Slot: ' . $slotParkir->kode_slot);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memproses parkir masuk: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cekSlotTersedia(Request $request)
    {
        $jenis_kendaraan = $request->input('jenis_kendaraan');
        
        $slotTersedia = SlotParkir::where('jenis_kendaraan', $jenis_kendaraan)
            ->where('status', 'tersedia')
            ->count();

        return response()->json([
            'slot_tersedia' => $slotTersedia
        ]);
    }
}
