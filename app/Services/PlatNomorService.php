<?php

namespace App\Services;

class PlatNomorService
{
    // Pola plat nomor untuk berbagai jenis kendaraan
    private $polaPlatNomor = [
        'motor' => [
            '/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/', // Standar motor
        ],
        'mobil' => [
            '/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/', // Standar mobil
            '/^B\s?[A-Z]{1,2}\s?\d{1,4}$/', // Plat khusus daerah
        ]
    ];

    public function deteksiJenisKendaraan($platNomor)
    {
        // Normalisasi plat nomor
        $platNomor = strtoupper(str_replace([' ', '-'], '', $platNomor));

        // Cek untuk mobil terlebih dahulu (kriteria lebih spesifik)
        foreach ($this->polaPlatNomor['mobil'] as $pola) {
            if (preg_match($pola, $platNomor)) {
                return 'mobil';
            }
        }

        // Cek untuk motor
        foreach ($this->polaPlatNomor['motor'] as $pola) {
            if (preg_match($pola, $platNomor)) {
                return 'motor';
            }
        }

        // Default atau lempar exception
        throw new \InvalidArgumentException('Format plat nomor tidak valid');
    }

    public function formatPlatNomor($platNomor)
    {
        // Normalisasi format plat nomor
        $platNomor = strtoupper(trim($platNomor));
        
        // Tambahkan spasi di tempat yang tepat
        $platNomor = preg_replace('/(\d{1,4})/', ' $1 ', $platNomor);
        
        return trim($platNomor);
    }

    public function validasiPlatNomor($platNomor)
    {
        try {
            $this->deteksiJenisKendaraan($platNomor);
            return true;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }
}
