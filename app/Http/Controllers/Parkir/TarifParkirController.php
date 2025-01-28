<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\TarifParkir;
use Illuminate\Http\Request;

class TarifParkirController extends Controller
{
    public function daftarTarif()
    {
        $tarifs = TarifParkir::all();
        return response()->json($tarifs);
    }

    public function tambahTarif(Request $request)
    {
        $validatedData = $request->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'tarif_per_jam' => 'required|numeric|min:0',
            'tarif_per_hari' => 'nullable|numeric|min:0',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'aktif' => 'boolean'
        ]);

        $tarif = TarifParkir::create($validatedData);

        return response()->json([
            'message' => 'Tarif parkir berhasil ditambahkan',
            'tarif' => $tarif
        ]);
    }

    public function updateTarif(Request $request, $id)
    {
        $tarif = TarifParkir::findOrFail($id);

        $validatedData = $request->validate([
            'tarif_per_jam' => 'numeric|min:0',
            'tarif_per_hari' => 'nullable|numeric|min:0',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'aktif' => 'boolean'
        ]);

        $tarif->update($validatedData);

        return response()->json([
            'message' => 'Tarif parkir berhasil diupdate',
            'tarif' => $tarif
        ]);
    }

    public function hapusTarif($id)
    {
        $tarif = TarifParkir::findOrFail($id);
        $tarif->delete();

        return response()->json([
            'message' => 'Tarif parkir berhasil dihapus'
        ]);
    }

    public function tarifAktif()
    {
        $tarifs = TarifParkir::aktif()->get();
        return response()->json($tarifs);
    }
}
