<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SimulasiParkir;
use App\Models\SkenarioSimulasi;
use Illuminate\Support\Facades\Validator;

class SimulasiParkirController extends Controller
{
    public function index()
    {
        $statistikSimulasi = SimulasiParkir::statistikSimulasi();
        $statistikSkenario = SkenarioSimulasi::statistikSkenario();

        $simulasiTerakhir = SimulasiParkir::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('simulasi-parkir.index', compact(
            'statistikSimulasi', 
            'statistikSkenario', 
            'simulasiTerakhir'
        ));
    }

    public function buatSimulasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'nullable|date',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'total_slot' => 'nullable|integer|min:10|max:500',
            'kapasitas_motor' => 'nullable|integer|min:0',
            'kapasitas_mobil' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $simulasi = SimulasiParkir::buatSimulasi($request->all());

        return response()->json([
            'status' => 'success',
            'simulasi' => $simulasi,
            'message' => 'Simulasi berhasil dibuat'
        ]);
    }

    public function jalankanSimulasi($simulasiId)
    {
        $simulasi = SimulasiParkir::findOrFail($simulasiId);
        
        // Update status simulasi
        $simulasi->update(['status_simulasi' => 'diproses']);

        // Jalankan simulasi
        $hasilSimulasi = $simulasi->jalankanSimulasi();

        return response()->json([
            'status' => 'success',
            'simulasi' => $hasilSimulasi,
            'message' => 'Simulasi berhasil dijalankan'
        ]);
    }

    public function tambahSkenario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'simulasi_id' => 'required|exists:simulasi_parkir,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'parameter' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $skenario = SkenarioSimulasi::buatSkenario($request->all());

        return response()->json([
            'status' => 'success',
            'skenario' => $skenario,
            'message' => 'Skenario berhasil ditambahkan'
        ]);
    }

    public function analisisKomparatif(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skenario_ids' => 'required|array|min:2',
            'skenario_ids.*' => 'exists:skenario_simulasi,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $analisis = SkenarioSimulasi::analisisKomparatif($request->input('skenario_ids'));

        return response()->json([
            'status' => 'success',
            'analisis' => $analisis
        ]);
    }

    public function daftarSimulasi()
    {
        $simulasi = SimulasiParkir::select('id', 'tanggal_simulasi', 'status_simulasi')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'simulasi' => $simulasi
        ]);
    }

    public function detailSimulasi($simulasiId)
    {
        $simulasi = SimulasiParkir::with('skenario')->findOrFail($simulasiId);

        return response()->json([
            'status' => 'success',
            'simulasi' => $simulasi
        ]);
    }
}
