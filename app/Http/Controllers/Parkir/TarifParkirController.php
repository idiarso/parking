<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\TarifParkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TarifParkirController extends Controller
{
    public function index()
    {
        $tarif = TarifParkir::orderBy('created_at', 'desc')->get();
        return view('tarif.index', compact('tarif'));
    }

    public function create()
    {
        return view('tarif.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_kendaraan' => [
                'required', 
                'in:motor,mobil',
                Rule::unique('tarif_parkir')->where(function ($query) use ($request) {
                    return $query->where('aktif', true);
                })
            ],
            'tarif_per_jam' => 'required|numeric|min:1000|max:50000',
            'tarif_per_hari' => 'nullable|numeric|min:10000|max:100000',
            'denda_per_jam' => 'nullable|numeric|min:1000|max:20000',
            'keterangan' => 'nullable|string|max:255'
        ], [
            'jenis_kendaraan.unique' => 'Tarif untuk jenis kendaraan ini sudah ada.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Non-aktifkan tarif sebelumnya untuk jenis kendaraan yang sama
        TarifParkir::where('jenis_kendaraan', $request->jenis_kendaraan)
            ->update(['aktif' => false]);

        // Buat tarif baru
        $tarif = TarifParkir::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam' => $request->tarif_per_jam,
            'tarif_per_hari' => $request->tarif_per_hari ?? 0,
            'denda_per_jam' => $request->denda_per_jam ?? 0,
            'keterangan' => $request->keterangan,
            'aktif' => true
        ]);

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif parkir berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tarif = TarifParkir::findOrFail($id);
        return view('tarif.edit', compact('tarif'));
    }

    public function update(Request $request, $id)
    {
        $tarif = TarifParkir::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'jenis_kendaraan' => [
                'required', 
                'in:motor,mobil',
                Rule::unique('tarif_parkir')
                    ->where(function ($query) use ($request, $id) {
                        return $query->where('aktif', true)
                                     ->where('id', '!=', $id);
                    })
            ],
            'tarif_per_jam' => 'required|numeric|min:1000|max:50000',
            'tarif_per_hari' => 'nullable|numeric|min:10000|max:100000',
            'denda_per_jam' => 'nullable|numeric|min:1000|max:20000',
            'keterangan' => 'nullable|string|max:255'
        ], [
            'jenis_kendaraan.unique' => 'Tarif untuk jenis kendaraan ini sudah ada.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update tarif
        $tarif->update([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam' => $request->tarif_per_jam,
            'tarif_per_hari' => $request->tarif_per_hari ?? 0,
            'denda_per_jam' => $request->denda_per_jam ?? 0,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif parkir berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tarif = TarifParkir::findOrFail($id);
        $tarif->delete();

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif parkir berhasil dihapus');
    }

    public function nonaktifkan($id)
    {
        $tarif = TarifParkir::findOrFail($id);
        $tarif->update(['aktif' => false]);

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif parkir berhasil dinonaktifkan');
    }

    public function aktifkan($id)
    {
        // Non-aktifkan tarif sejenis yang sedang aktif
        TarifParkir::where('jenis_kendaraan', $tarif->jenis_kendaraan)
            ->update(['aktif' => false]);

        $tarif = TarifParkir::findOrFail($id);
        $tarif->update(['aktif' => true]);

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif parkir berhasil diaktifkan');
    }

    public function riwayatTarif()
    {
        $riwayatTarif = TarifParkir::withTrashed()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tarif.riwayat', compact('riwayatTarif'));
    }
}
