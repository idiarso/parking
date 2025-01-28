<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\SlotParkir;
use Illuminate\Http\Request;

class SlotParkirController extends Controller
{
    public function daftarSlot()
    {
        $slots = SlotParkir::with('kendaraan')->get();
        return response()->json($slots);
    }

    public function slotKosong()
    {
        $slots = SlotParkir::kosong()->get();
        return response()->json($slots);
    }

    public function buatSlot(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_slot' => 'required|unique:slot_parkir,nomor_slot',
            'jenis_kendaraan' => 'required|in:motor,mobil'
        ]);

        $slot = SlotParkir::create([
            'nomor_slot' => $validatedData['nomor_slot'],
            'jenis_kendaraan' => $validatedData['jenis_kendaraan'],
            'status' => 'kosong'
        ]);

        return response()->json([
            'message' => 'Slot parkir berhasil dibuat',
            'slot' => $slot
        ]);
    }

    public function updateSlot(Request $request, $id)
    {
        $slot = SlotParkir::findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'in:kosong,terisi',
            'jenis_kendaraan' => 'in:motor,mobil'
        ]);

        $slot->update($validatedData);

        return response()->json([
            'message' => 'Slot parkir berhasil diupdate',
            'slot' => $slot
        ]);
    }

    public function hapusSlot($id)
    {
        $slot = SlotParkir::findOrFail($id);
        $slot->delete();

        return response()->json([
            'message' => 'Slot parkir berhasil dihapus'
        ]);
    }
}
