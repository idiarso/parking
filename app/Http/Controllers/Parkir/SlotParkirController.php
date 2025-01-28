<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SlotParkirController extends Controller
{
    public function index(Request $request)
    {
        $query = SlotParkir::query();

        // Filter berdasarkan jenis kendaraan
        if ($request->has('jenis_kendaraan')) {
            $query->where('jenis_kendaraan', $request->jenis_kendaraan);
        }

        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $slotParkir = $query->paginate(10);

        return view('slot-parkir.index', [
            'slotParkir' => $slotParkir,
            'statusTersedia' => SlotParkir::hitungSlotTersedia(),
            'statusMotor' => SlotParkir::hitungSlotTersedia('motor'),
            'statusMobil' => SlotParkir::hitungSlotTersedia('mobil')
        ]);
    }

    public function create()
    {
        return view('slot-parkir.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => [
                'required', 
                'unique:slot_parkir,nomor', 
                'max:10'
            ],
            'jenis_kendaraan' => [
                'required', 
                Rule::in(['motor', 'mobil'])
            ],
            'lokasi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $slotParkir = SlotParkir::create([
            'nomor' => $request->nomor,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'status' => 'kosong',
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('slot-parkir.index')
            ->with('success', "Slot parkir {$slotParkir->nomor} berhasil ditambahkan");
    }

    public function edit(SlotParkir $slotParkir)
    {
        return view('slot-parkir.edit', compact('slotParkir'));
    }

    public function update(Request $request, SlotParkir $slotParkir)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => [
                'required', 
                "unique:slot_parkir,nomor,{$slotParkir->id}", 
                'max:10'
            ],
            'jenis_kendaraan' => [
                'required', 
                Rule::in(['motor', 'mobil'])
            ],
            'status' => [
                'required', 
                Rule::in(['kosong', 'terisi', 'rusak', 'maintenance'])
            ],
            'lokasi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $slotParkir->update([
            'nomor' => $request->nomor,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'status' => $request->status,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('slot-parkir.index')
            ->with('success', "Slot parkir {$slotParkir->nomor} berhasil diperbarui");
    }

    public function destroy(SlotParkir $slotParkir)
    {
        // Cek apakah slot sedang digunakan
        if ($slotParkir->kendaraan) {
            return redirect()->back()
                ->with('error', "Slot parkir {$slotParkir->nomor} tidak bisa dihapus karena sedang digunakan");
        }

        $slotParkir->delete();

        return redirect()->route('slot-parkir.index')
            ->with('success', "Slot parkir {$slotParkir->nomor} berhasil dihapus");
    }

    // Metode khusus untuk mengubah status slot
    public function ubahStatus(Request $request, SlotParkir $slotParkir)
    {
        $validator = Validator::make($request->all(), [
            'status' => [
                'required', 
                Rule::in(['kosong', 'terisi', 'rusak', 'maintenance'])
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $slotParkir->ubahStatus($request->status);

            return response()->json([
                'success' => true,
                'message' => "Status slot {$slotParkir->nomor} berhasil diubah"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
