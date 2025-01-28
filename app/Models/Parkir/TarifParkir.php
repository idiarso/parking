<?php

namespace App\Models\Parkir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TarifParkir extends Model
{
    use SoftDeletes;

    protected $table = 'tarif_parkir';

    protected $fillable = [
        'jenis_kendaraan', 
        'tarif_per_jam', 
        'tarif_per_hari', 
        'denda_per_jam', 
        'keterangan', 
        'aktif',
        'jam_mulai',
        'jam_selesai'
    ];

    protected $casts = [
        'tarif_per_jam' => 'integer',
        'tarif_per_hari' => 'integer',
        'denda_per_jam' => 'integer',
        'aktif' => 'boolean',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i'
    ];

    // Scope untuk tarif yang aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope untuk tarif berdasarkan jenis kendaraan
    public function scopeJenisKendaraan($query, $jenis)
    {
        return $query->where('jenis_kendaraan', $jenis);
    }

    // Metode untuk menghitung biaya parkir
    public function hitungBiayaParkir($durasi)
    {
        // Jika durasi kurang dari 1 jam, dikenakan tarif per jam
        if ($durasi <= 1) {
            return $this->tarif_per_jam;
        }

        // Jika durasi lebih dari 24 jam, gunakan tarif per hari
        if ($durasi > 24) {
            $hari = floor($durasi / 24);
            $sisaJam = $durasi % 24;
            
            $biayaHarian = $hari * $this->tarif_per_hari;
            $biayaJam = $sisaJam > 0 ? $this->tarif_per_jam * $sisaJam : 0;
            
            return $biayaHarian + $biayaJam;
        }

        // Untuk durasi antara 1-24 jam, gunakan tarif per jam
        return $this->tarif_per_jam * ceil($durasi);
    }

    // Metode untuk menghitung denda
    public function hitungDenda($durasi)
    {
        // Contoh: Jika parkir lebih dari 12 jam, kenakan denda per jam
        if ($durasi > 12) {
            $jamLebih = $durasi - 12;
            return $this->denda_per_jam * ceil($jamLebih);
        }
        
        return 0;
    }

    // Getter untuk format mata uang
    public function getTarifPerJamFormatAttribute()
    {
        return 'Rp ' . number_format($this->tarif_per_jam, 0, ',', '.');
    }

    public function getTarifPerHariFormatAttribute()
    {
        return 'Rp ' . number_format($this->tarif_per_hari, 0, ',', '.');
    }

    public function getDendaPerJamFormatAttribute()
    {
        return 'Rp ' . number_format($this->denda_per_jam, 0, ',', '.');
    }
}
