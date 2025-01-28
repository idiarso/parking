<?php

namespace App\Http\Controllers\Parkir;

use App\Http\Controllers\Controller;
use App\Models\Parkir\Kendaraan;
use Illuminate\Http\Request;

class ParkirController extends Controller
{
    public function masukKendaraan(Request $request)
    {
        // Logika pendaftaran kendaraan masuk
    }

    public function keluarKendaraan(Request $request)
    {
        // Logika proses kendaraan keluar
    }

    public function cekStatusParkir()
    {
        // Menampilkan status slot parkir
    }
}
