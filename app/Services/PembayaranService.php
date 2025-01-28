<?php

namespace App\Services;

use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\TarifParkir;
use App\Models\Pembayaran;
use Illuminate\Support\Str;

class PembayaranService
{
    public function prosesPembayaran(Kendaraan $kendaraan, TarifParkir $tarif, $metodePembayaran)
    {
        // Validasi metode pembayaran
        $this->validasiMetodePembayaran($metodePembayaran);

        // Hitung total biaya dengan potensi diskon atau denda
        $totalBiaya = $this->hitungTotalBiaya($kendaraan, $tarif);

        // Buat entri pembayaran
        $pembayaran = Pembayaran::create([
            'kendaraan_id' => $kendaraan->id,
            'nomor_transaksi' => $this->generateNomorTransaksi(),
            'metode_pembayaran' => $metodePembayaran,
            'total_biaya' => $totalBiaya,
            'status_pembayaran' => 'lunas'
        ]);

        return $pembayaran;
    }

    private function validasiMetodePembayaran($metode)
    {
        $metodValid = ['tunai', 'transfer', 'qris'];
        
        if (!in_array($metode, $metodValid)) {
            throw new \InvalidArgumentException('Metode pembayaran tidak valid');
        }
    }

    private function hitungTotalBiaya(Kendaraan $kendaraan, TarifParkir $tarif)
    {
        $biayaDasar = $kendaraan->durasi_parkir * $tarif->tarif_per_jam;
        
        // Tambahkan denda jika melebihi durasi maksimal
        $denda = 0;
        if ($kendaraan->durasi_parkir > $tarif->durasi_maksimal) {
            $jamTerlambat = $kendaraan->durasi_parkir - $tarif->durasi_maksimal;
            $denda = $jamTerlambat * $tarif->denda_per_jam;
        }

        return $biayaDasar + $denda;
    }

    private function generateNomorTransaksi()
    {
        // Format: TRXPARKIR-YYYYMMDD-RANDOMSTRING
        $tanggal = now()->format('Ymd');
        $randomString = Str::random(8);
        return "TRXPARKIR-{$tanggal}-{$randomString}";
    }

    public function cekDiskon(Kendaraan $kendaraan, TarifParkir $tarif)
    {
        // Logika diskon berdasarkan durasi atau jenis kendaraan
        $diskon = 0;

        // Contoh: Diskon 10% jika parkir lebih dari 12 jam
        if ($kendaraan->durasi_parkir > 12) {
            $diskon = 0.1; // 10%
        }

        return $diskon;
    }
}
