<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perangkat;
use App\Models\LogPemeliharaan;
use Illuminate\Support\Facades\Validator;

class ManajemenPerangkatController extends Controller
{
    public function index()
    {
        $statistikPerangkat = Perangkat::statistikPerangkat();
        $logPemeliharaan = LogPemeliharaan::statistikPemeliharaan();

        $perangkat = Perangkat::all();

        return view('manajemen-perangkat.index', compact(
            'statistikPerangkat', 
            'logPemeliharaan', 
            'perangkat'
        ));
    }

    public function tambahPerangkat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:' . implode(',', array_keys(Perangkat::JENIS)),
            'lokasi' => 'required|string|max:255',
            'ip_address' => 'nullable|ip',
            'mac_address' => 'nullable|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'serial_number' => 'nullable|unique:perangkat,serial_number'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $perangkat = Perangkat::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'perangkat' => $perangkat,
            'message' => 'Perangkat berhasil ditambahkan'
        ]);
    }

    public function updateStatus(Request $request, $perangkatId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', array_keys(Perangkat::STATUS))
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $perangkat = Perangkat::findOrFail($perangkatId);
        $perangkat->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'perangkat' => $perangkat,
            'message' => 'Status perangkat berhasil diperbarui'
        ]);
    }

    public function periksaKesehatan($perangkatId)
    {
        $perangkat = Perangkat::findOrFail($perangkatId);
        $kondisi = $perangkat->periksaKesehatan();

        return response()->json([
            'status' => 'success',
            'kondisi' => $kondisi
        ]);
    }

    public function catatPemeliharaan(Request $request, $perangkatId)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string',
            'status' => 'required|in:' . implode(',', array_keys(LogPemeliharaan::STATUS)),
            'biaya' => 'nullable|numeric',
            'suku_cadang_diganti' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $perangkat = Perangkat::findOrFail($perangkatId);
        $logPemeliharaan = $perangkat->catatPemeliharaan(
            $request->input('deskripsi'),
            $request->input('status')
        );

        // Update status perangkat jika dalam pemeliharaan
        if ($request->input('status') === 'proses') {
            $perangkat->update(['status' => 'pemeliharaan']);
        }

        return response()->json([
            'status' => 'success',
            'log_pemeliharaan' => $logPemeliharaan,
            'message' => 'Log pemeliharaan berhasil dicatat'
        ]);
    }

    public function daftarPerangkat()
    {
        return response()->json([
            'jenis_perangkat' => Perangkat::JENIS,
            'status_perangkat' => Perangkat::STATUS
        ]);
    }
}
