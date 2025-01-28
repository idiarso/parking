<?php

namespace App\Services;

use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\TarifParkir;
use Illuminate\Support\Str;

class TiketParkirService
{
    public function generateTiket(Kendaraan $kendaraan, TarifParkir $tarif)
    {
        return [
            'nomor_tiket' => $this->generateNomorTiket(),
            'plat_nomor' => $kendaraan->plat_nomor,
            'jenis_kendaraan' => $kendaraan->jenis_kendaraan,
            'waktu_masuk' => $kendaraan->waktu_masuk,
            'tarif_per_jam' => $tarif->tarif_per_jam,
            'qr_code' => $this->generateQRCode($kendaraan)
        ];
    }

    private function generateNomorTiket()
    {
        // Format: PARKIR-YYYYMMDD-RANDOMSTRING
        $tanggal = now()->format('Ymd');
        $randomString = Str::random(6);
        return "PARKIR-{$tanggal}-{$randomString}";
    }

    private function generateQRCode(Kendaraan $kendaraan)
    {
        // Generate QR Code berisi informasi kendaraan
        $data = json_encode([
            'plat_nomor' => $kendaraan->plat_nomor,
            'waktu_masuk' => $kendaraan->waktu_masuk->toIso8601String(),
            'id_kendaraan' => $kendaraan->id
        ]);

        // Gunakan library QR Code untuk generate
        return \QrCode::size(200)
            ->generate($data);
    }
}
