<?php

namespace App\Models\Parkir;

use Illuminate\Database\Eloquent\Model;

class TarifParkir extends Model
{
    protected $table = 'tarif_parkir';

    protected $fillable = [
        'jenis_kendaraan',
        'tarif_per_jam',
        'tarif_per_hari',
        'jam_mulai',
        'jam_selesai',
        'aktif'
    ];

    // Scope untuk tarif aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Metode untuk mendapatkan tarif berdasarkan jenis kendaraan
    public static function getTarifByJenisKendaraan($jenisKendaraan)
    {
        return self::where('jenis_kendaraan', $jenisKendaraan)
                   ->where('aktif', true)
                   ->first();
    }

    // Metode untuk mengubah tarif
    public function ubahTarif($tarifBaru)
    {
        $this->update([
            'tarif_per_jam' => $tarifBaru['tarif_per_jam'],
            'tarif_per_hari' => $tarifBaru['tarif_per_hari'] ?? null
        ]);

        return $this;
    }
}
