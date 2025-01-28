<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\TarifParkir;
use App\Services\PlatNomorService;
use App\Services\TiketParkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class PintuMasukController extends Controller
{
    protected $platNomorService;
    protected $tiketParkirService;

    public function __construct(PlatNomorService $platNomorService, TiketParkirService $tiketParkirService)
    {
        $this->platNomorService = $platNomorService;
        $this->tiketParkirService = $tiketParkirService;
    }

    public function index()
    {
        $slotTersedia = SlotParkir::where('status', 'kosong')->get();
        return view('pintu-masuk.index', compact('slotTersedia'));
    }

    public function prosesKendaraanMasuk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plat_nomor' => [
                'required', 
                'regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/',
            ],
            'slot_parkir_id' => 'required|exists:slot_parkir,id',
            'kondisi_kendaraan' => 'nullable|string|max:255',
            'foto_kendaraan' => 'nullable|image|max:5120' // Maks 5MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Deteksi jenis kendaraan dari plat nomor
            $jenisKendaraan = $this->platNomorService->deteksiJenisKendaraan($request->plat_nomor);

            // Cek ketersediaan slot
            $slotParkir = SlotParkir::findOrFail($request->slot_parkir_id);
            if ($slotParkir->status !== 'kosong') {
                throw new \Exception('Slot parkir sudah terisi');
            }

            // Dapatkan tarif aktif
            $tarif = TarifParkir::where('jenis_kendaraan', $jenisKendaraan)
                ->where('aktif', true)
                ->firstOrFail();

            // Simpan foto kendaraan jika ada
            $fotoPath = null;
            if ($request->hasFile('foto_kendaraan')) {
                $fotoPath = $request->file('foto_kendaraan')->store('kondisi_kendaraan', 'public');
            }

            // Buat entri kendaraan
            $kendaraan = Kendaraan::create([
                'plat_nomor' => $request->plat_nomor,
                'jenis_kendaraan' => $jenisKendaraan,
                'waktu_masuk' => now(),
                'status' => 'parkir',
                'catatan' => $request->kondisi_kendaraan,
                'foto_kondisi' => $fotoPath
            ]);

            // Update status slot parkir
            $slotParkir->update([
                'status' => 'terisi',
                'kendaraan_id' => $kendaraan->id
            ]);

            // Generate tiket parkir
            $tiket = $this->tiketParkirService->generateTiket($kendaraan, $tarif);

            DB::commit();

            // Cetak tiket
            $pdf = PDF::loadView('tiket.parkir', compact('kendaraan', 'tiket', 'tarif'));
            return $pdf->download("tiket_parkir_{$kendaraan->plat_nomor}.pdf");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
}
