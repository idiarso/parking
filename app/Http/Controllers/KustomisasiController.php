<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KustomisasiController extends Controller
{
    public function index()
    {
        // Data Tema
        $tema = [
            (object)[
                'id' => 1,
                'nama' => 'Klasik Biru',
                'deskripsi' => 'Tema profesional dengan nuansa biru',
                'warna_utama' => '#3498db',
                'warna_aksen' => '#2980b9',
                'aktif' => true
            ],
            (object)[
                'id' => 2,
                'nama' => 'Hijau Modern',
                'deskripsi' => 'Tema segar dengan warna hijau',
                'warna_utama' => '#2ecc71',
                'warna_aksen' => '#27ae60',
                'aktif' => false
            ],
            (object)[
                'id' => 3,
                'nama' => 'Gelap Elegan',
                'deskripsi' => 'Tema gelap untuk penggunaan malam hari',
                'warna_utama' => '#34495e',
                'warna_aksen' => '#2c3e50',
                'aktif' => false
            ]
        ];

        // Komponen Aktif
        $komponenAktif = [
            (object)[
                'id' => 1,
                'nama' => 'Dashboard Utama',
                'icon' => 'fas fa-chart-pie',
                'aktif' => true
            ],
            (object)[
                'id' => 2,
                'nama' => 'Laporan Parkir',
                'icon' => 'fas fa-file-alt',
                'aktif' => true
            ],
            (object)[
                'id' => 3,
                'nama' => 'Monitoring Real-time',
                'icon' => 'fas fa-satellite-dish',
                'aktif' => true
            ],
            (object)[
                'id' => 4,
                'nama' => 'Simulasi Parkir',
                'icon' => 'fas fa-calculator',
                'aktif' => false
            ]
        ];

        // Pengaturan Aksesibilitas
        $aksesibilitas = [
            (object)[
                'id' => 1,
                'nama' => 'Mode Kontras Tinggi',
                'deskripsi' => 'Tingkatkan keterbacaan untuk penglihatan terbatas',
                'icon' => 'fas fa-adjust',
                'aktif' => false
            ],
            (object)[
                'id' => 2,
                'nama' => 'Ukuran Teks Besar',
                'deskripsi' => 'Perbesar teks untuk kemudahan membaca',
                'icon' => 'fas fa-text-height',
                'aktif' => false
            ],
            (object)[
                'id' => 3,
                'nama' => 'Pembaca Layar',
                'deskripsi' => 'Aktifkan kompatibilitas pembaca layar',
                'icon' => 'fas fa-volume-up',
                'aktif' => false
            ]
        ];

        // Preferensi Pengguna
        $preferensiPengguna = [
            (object)[
                'id' => 'bahasa',
                'nama' => 'Bahasa Antarmuka',
                'icon' => 'fas fa-language',
                'tipe' => 'select',
                'deskripsi' => 'Pilih bahasa yang diinginkan',
                'pilihan' => [
                    (object)['value' => 'id', 'label' => 'Indonesia', 'selected' => true],
                    (object)['value' => 'en', 'label' => 'English', 'selected' => false]
                ]
            ],
            (object)[
                'id' => 'kecerahan',
                'nama' => 'Kecerahan Layar',
                'icon' => 'fas fa-adjust',
                'tipe' => 'range',
                'deskripsi' => 'Atur kecerahan untuk kenyamanan mata',
                'min' => 20,
                'max' => 100,
                'value' => 70
            ]
        ];

        return view('kustomisasi.index', compact(
            'tema', 
            'komponenAktif', 
            'aksesibilitas', 
            'preferensiPengguna'
        ));
    }

    public function aktifkanTema(Request $request)
    {
        $validatedData = $request->validate([
            'tema_id' => 'required|exists:tema,id'
        ]);

        // Simpan preferensi tema untuk pengguna
        $user = Auth::user();
        $user->tema_id = $validatedData['tema_id'];
        $user->save();

        return response()->json([
            'status' => 'success',
            'kelas_tema' => 'tema-' . $validatedData['tema_id']
        ]);
    }

    public function toggleKomponen(Request $request)
    {
        $validatedData = $request->validate([
            'komponen_id' => 'required|exists:komponen,id',
            'aktif' => 'required|boolean'
        ]);

        // Simpan status komponen
        $user = Auth::user();
        $user->komponen_settings()->updateOrCreate(
            ['komponen_id' => $validatedData['komponen_id']],
            ['aktif' => $validatedData['aktif']]
        );

        return response()->json([
            'status' => 'success',
            'nama' => 'Komponen ' . $validatedData['komponen_id']
        ]);
    }

    public function toggleAksesibilitas(Request $request)
    {
        $validatedData = $request->validate([
            'aksesibilitas_id' => 'required|exists:aksesibilitas,id',
            'aktif' => 'required|boolean'
        ]);

        // Simpan pengaturan aksesibilitas
        $user = Auth::user();
        $user->aksesibilitas_settings()->updateOrCreate(
            ['aksesibilitas_id' => $validatedData['aksesibilitas_id']],
            ['aktif' => $validatedData['aktif']]
        );

        return response()->json([
            'status' => 'success',
            'nama' => 'Aksesibilitas ' . $validatedData['aksesibilitas_id'],
            'kelas_aksesibilitas' => $validatedData['aktif'] ? 'aksesibilitas-aktif' : null
        ]);
    }

    public function simpanPreferensi(Request $request)
    {
        $user = Auth::user();

        // Validasi dan simpan preferensi dinamis
        foreach ($request->all() as $key => $value) {
            $user->preferensi()->updateOrCreate(
                ['kunci' => $key],
                ['nilai' => $value]
            );
        }

        return response()->json([
            'status' => 'success',
            'pesan' => 'Preferensi berhasil disimpan'
        ]);
    }
}
