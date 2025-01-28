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

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'durasi_parkir' => 'float',
        'biaya_parkir' => 'float'
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

    // Mutator untuk waktu masuk
    public function setWaktuMasukAttribute($value)
    {
        $this->attributes['waktu_masuk'] = is_string($value) 
            ? Carbon::parse($value) 
            : ($value instanceof Carbon ? $value : now());
    }

    // Mutator untuk waktu keluar
    public function setWaktuKeluarAttribute($value)
    {
        $this->attributes['waktu_keluar'] = is_string($value) 
            ? Carbon::parse($value) 
            : ($value instanceof Carbon ? $value : null);
    }

    // Hitung durasi parkir dalam jam
    public function hitungDurasiParkir()
    {
        if (!$this->waktu_masuk || !$this->waktu_keluar) {
            return 0;
        }

        $durasi = $this->waktu_masuk->diffInMinutes($this->waktu_keluar);
        return max(1, ceil($durasi / 60)); // Minimal 1 jam
    }

    // Hitung biaya parkir berdasarkan durasi dan tarif
    public function hitungBiayaParkir($tarifPerjam)
    {
        $durasi = $this->hitungDurasiParkir();
        return $durasi * $tarifPerjam;
    }

    // Accessor untuk durasi parkir format
    public function getDurasiParkirFormatAttribute()
    {
        $jam = floor($this->durasi_parkir);
        $menit = round(($this->durasi_parkir - $jam) * 60);
        return "{$jam} jam {$menit} menit";
    }

    // Accessor untuk biaya parkir format
    public function getBiayaParkirFormatAttribute()
    {
        return 'Rp ' . number_format($this->biaya_parkir, 0, ',', '.');
    }

    // Scope untuk filter kendaraan yang sedang parkir
    public function scopeSedangParkir($query)
    {
        return $query->where('status', 'parkir');
    }

    // Scope untuk pencarian berdasarkan plat nomor
    public function scopeCariPlatNomor($query, $platNomor)
    {
        return $query->where('plat_nomor', 'like', "%{$platNomor}%");
    }

    // Scope untuk filter jenis kendaraan
    public function scopeJenisKendaraan($query, $jenis)
    {
        return $query->where('jenis_kendaraan', $jenis);
    }

    // Scope untuk filter status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk filter rentang waktu
    public function scopeRentangWaktu($query, $mulai, $selesai)
    {
        return $query->whereBetween('waktu_masuk', [$mulai, $selesai]);
    }

    // Metode untuk mendapatkan kendaraan aktif (masih parkir)
    public static function kendaraanAktif()
    {
        return self::where('status', 'parkir')->get();
    }

    // Metode untuk mendapatkan riwayat kendaraan
    public static function riwayatKendaraan($limit = 50)
    {
        return self::orderBy('waktu_masuk', 'desc')->limit($limit)->get();
    }
}
