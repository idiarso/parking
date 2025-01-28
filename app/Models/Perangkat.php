<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Perangkat extends Model
{
    use HasFactory;

    protected $table = 'perangkat';

    protected $fillable = [
        'nama', 
        'jenis', 
        'lokasi', 
        'status', 
        'terakhir_dipelihara', 
        'kondisi', 
        'ip_address', 
        'mac_address', 
        'serial_number',
        'catatan_pemeliharaan'
    ];

    protected $dates = [
        'terakhir_dipelihara'
    ];

    protected $casts = [
        'aktif' => 'boolean'
    ];

    // Jenis Perangkat
    const JENIS = [
        'kamera_masuk' => 'Kamera Pintu Masuk',
        'kamera_keluar' => 'Kamera Pintu Keluar', 
        'sensor_parkir' => 'Sensor Parkir',
        'barrier_gate' => 'Palang Pintu',
        'display_informasi' => 'Layar Informasi',
        'printer_struk' => 'Printer Struk',
        'server' => 'Server Utama'
    ];

    // Status Perangkat
    const STATUS = [
        'aktif' => 'Aktif',
        'tidak_aktif' => 'Tidak Aktif', 
        'pemeliharaan' => 'Dalam Pemeliharaan',
        'rusak' => 'Rusak'
    ];

    // Relasi dengan Log Pemeliharaan
    public function logPemeliharaan()
    {
        return $this->hasMany(LogPemeliharaan::class, 'perangkat_id');
    }

    // Scope untuk filter status
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeDalamPemeliharaan($query)
    {
        return $query->where('status', 'pemeliharaan');
    }

    // Method untuk mengecek kesehatan perangkat
    public function periksaKesehatan()
    {
        // Logika pengecekan kesehatan perangkat
        $kondisi = [
            'status_koneksi' => $this->periksaKoneksi(),
            'terakhir_dipelihara' => $this->cekJadwalPemeliharaan()
        ];

        return $kondisi;
    }

    private function periksaKoneksi()
    {
        // Simulasi pengecekan koneksi
        try {
            $status = @fsockopen($this->ip_address, 80, $errno, $errstr, 5);
            return $status ? 'online' : 'offline';
        } catch (\Exception $e) {
            return 'offline';
        }
    }

    private function cekJadwalPemeliharaan()
    {
        $terakhirPemeliharaan = $this->terakhir_dipelihara;
        $selisihBulan = $terakhirPemeliharaan ? $terakhirPemeliharaan->diffInMonths(now()) : null;

        return [
            'terakhir' => $terakhirPemeliharaan,
            'selisih_bulan' => $selisihBulan,
            'perlu_pemeliharaan' => $selisihBulan > 3
        ];
    }

    // Catat log pemeliharaan
    public function catatPemeliharaan($deskripsi, $status = 'selesai')
    {
        return $this->logPemeliharaan()->create([
            'deskripsi' => $deskripsi,
            'teknisi' => auth()->user()->name ?? 'Sistem',
            'status' => $status
        ]);
    }

    // Statistik perangkat
    public static function statistikPerangkat()
    {
        return [
            'total' => self::count(),
            'aktif' => self::where('status', 'aktif')->count(),
            'pemeliharaan' => self::where('status', 'pemeliharaan')->count(),
            'rusak' => self::where('status', 'rusak')->count(),
            'per_jenis' => self::select('jenis', \DB::raw('COUNT(*) as total'))
                        ->groupBy('jenis')
                        ->get()
        ];
    }
}
