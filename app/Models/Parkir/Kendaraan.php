<?php

namespace App\Models\Parkir;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Parkir\SlotParkir;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    protected $fillable = [
        'plat_nomor',
        'jenis_kendaraan',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_parkir',
        'biaya_parkir',
        'status',
        'catatan'
    ];

    protected $dates = [
        'waktu_masuk',
        'waktu_keluar'
    ];

    // Relasi dengan slot parkir
    public function slotParkir()
    {
        return $this->hasOne(SlotParkir::class);
    }

    // Metode untuk mencatat kendaraan masuk
    public function masuk($platNomor, $jenisKendaraan)
    {
        return self::create([
            'plat_nomor' => $platNomor,
            'jenis_kendaraan' => $jenisKendaraan,
            'waktu_masuk' => now(),
            'status' => 'parkir'
        ]);
    }

    // Metode untuk mencatat kendaraan keluar
    public function keluar($tarifPerjam)
    {
        $this->waktu_keluar = now();
        $this->durasi_parkir = $this->hitungDurasiParkir();
        $this->biaya_parkir = $this->hitungBiayaParkir($tarifPerjam);
        $this->status = 'keluar';
        $this->save();

        return $this;
    }

    // Hitung durasi parkir dalam jam
    public function hitungDurasiParkir()
    {
        $masuk = Carbon::parse($this->waktu_masuk);
        $keluar = Carbon::parse($this->waktu_keluar);
        
        return ceil($masuk->diffInMinutes($keluar) / 60);
    }

    // Hitung biaya parkir berdasarkan durasi dan tarif
    public function hitungBiayaParkir($tarifPerjam)
    {
        $durasi = $this->hitungDurasiParkir();
        return $durasi * $tarifPerjam;
    }

    // Scope untuk filter kendaraan yang sedang parkir
    public function scopeSedangParkir($query)
    {
        return $query->where('status', 'parkir');
    }
}
