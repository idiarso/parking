<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class InfrastrukturController extends Controller
{
    public function index()
    {
        // Daftar Perangkat
        $daftarPerangkat = [
            (object)[
                'id' => 1,
                'nama' => 'Kamera ANPR',
                'serial_number' => 'CAM-001',
                'status' => 'aktif',
                'kesehatan' => 85,
                'icon' => 'fas fa-camera'
            ],
            (object)[
                'id' => 2,
                'nama' => 'Barrier Gate Masuk',
                'serial_number' => 'GATE-IN-001',
                'status' => 'gangguan',
                'kesehatan' => 45,
                'icon' => 'fas fa-door-open'
            ],
            (object)[
                'id' => 3,
                'nama' => 'Printer Tiket',
                'serial_number' => 'PRT-001',
                'status' => 'perawatan',
                'kesehatan' => 60,
                'icon' => 'fas fa-print'
            ],
            (object)[
                'id' => 4,
                'nama' => 'Server Utama',
                'serial_number' => 'SRV-001',
                'status' => 'aktif',
                'kesehatan' => 90,
                'icon' => 'fas fa-server'
            ]
        ];

        // Status Infrastruktur
        $statusInfrastruktur = [
            (object)[
                'nama' => 'Jaringan',
                'persentase' => 95,
                'icon' => 'fas fa-wifi'
            ],
            (object)[
                'nama' => 'Perangkat Keras',
                'persentase' => 65,
                'icon' => 'fas fa-desktop'
            ],
            (object)[
                'nama' => 'Sistem Keamanan',
                'persentase' => 80,
                'icon' => 'fas fa-shield-alt'
            ]
        ];

        // Log Pemeliharaan
        $logPemeliharaan = [
            (object)[
                'tanggal' => Carbon::now()->subDays(3),
                'nama_perangkat' => 'Barrier Gate Masuk',
                'jenis_pemeliharaan' => 'Perbaikan Motor',
                'status' => 'selesai',
                'teknisi' => 'Ahmad Syahid'
            ],
            (object)[
                'tanggal' => Carbon::now()->subDays(1),
                'nama_perangkat' => 'Kamera ANPR',
                'jenis_pemeliharaan' => 'Kalibrasi',
                'status' => 'proses',
                'teknisi' => 'Budi Santoso'
            ],
            (object)[
                'tanggal' => Carbon::now()->subWeeks(2),
                'nama_perangkat' => 'Server Utama',
                'jenis_pemeliharaan' => 'Update Firmware',
                'status' => 'selesai',
                'teknisi' => 'Rini Kusuma'
            ]
        ];

        return view('infrastruktur.index', compact(
            'daftarPerangkat', 
            'statusInfrastruktur', 
            'logPemeliharaan'
        ));
    }

    public function jadwalkanPemeliharaan(Request $request)
    {
        $validatedData = $request->validate([
            'perangkat_id' => 'required|exists:perangkat,id',
            'tanggal' => 'required|date|after:today',
            'catatan' => 'nullable|string|max:500'
        ]);

        // Simpan jadwal pemeliharaan
        // Implementasi sesuai kebutuhan sistem

        return response()->json([
            'status' => 'success',
            'message' => 'Pemeliharaan berhasil dijadwalkan'
        ]);
    }

    public function laporkanMasalah(Request $request)
    {
        $validatedData = $request->validate([
            'perangkat_id' => 'required|exists:perangkat,id',
            'deskripsi' => 'required|string|max:1000',
            'urgensi' => 'required|in:rendah,sedang,tinggi'
        ]);

        // Simpan laporan masalah
        // Implementasi sesuai kebutuhan sistem

        return response()->json([
            'status' => 'success',
            'message' => 'Masalah berhasil dilaporkan'
        ]);
    }

    public function restartSistem()
    {
        // Implementasi restart sistem
        // Misalnya, mengirim perintah restart ke berbagai subsistem

        return response()->json([
            'status' => 'success',
            'message' => 'Sistem parkir sedang direstart'
        ]);
    }
}
