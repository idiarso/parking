<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'kendaraan_id', 
        'total_bayar', 
        'metode_pembayaran', 
        'status_pembayaran', 
        'waktu_masuk', 
        'waktu_keluar', 
        'durasi_parkir',
        'potongan_harga',
        'pajak',
        'kode_transaksi'
    ];

    protected $dates = [
        'waktu_masuk', 
        'waktu_keluar'
    ];

    protected $casts = [
        'total_bayar' => 'float',
        'potongan_harga' => 'float',
        'pajak' => 'float',
        'durasi_parkir' => 'integer'
    ];

    // Relasi dengan Kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    // Scope untuk berbagai jenis pembayaran
    public function scopeSelesai($query)
    {
        return $query->where('status_pembayaran', 'selesai');
    }

    public function scopeBelumBayar($query)
    {
        return $query->where('status_pembayaran', 'pending');
    }

    // Method untuk menghitung tarif
    public static function hitungTarif(Kendaraan $kendaraan, $waktuMasuk, $waktuKeluar)
    {
        $durasi = $waktuMasuk->diffInHours($waktuKeluar);
        $tarifDasar = $kendaraan->jenis === 'motor' ? 3000 : 5000;
        
        // Tarif progresif
        $tarifTotal = $tarifDasar * ($durasi > 0 ? $durasi : 1);
        
        // Diskon untuk durasi panjang
        $diskon = $durasi > 12 ? 0.2 : 0;
        
        return [
            'tarif_dasar' => $tarifDasar,
            'durasi' => $durasi,
            'total_bayar' => $tarifTotal * (1 - $diskon),
            'diskon' => $diskon * 100
        ];
    }

    // Generate kode transaksi unik
    public static function generateKodeTransaksi()
    {
        return 'PK-' . strtoupper(substr(uniqid(), -6)) . '-' . date('Ymd');
    }

    // Metode untuk memproses pembayaran
    public function prosesPembayaran($metodePembayaran)
    {
        $this->metode_pembayaran = $metodePembayaran;
        $this->status_pembayaran = 'selesai';
        $this->save();

        return $this;
    }

    // Statistik pembayaran
    public static function statistikHarian()
    {
        return self::select(
            \DB::raw('DATE(created_at) as tanggal'),
            \DB::raw('SUM(total_bayar) as total_pendapatan'),
            \DB::raw('COUNT(*) as total_transaksi')
        )
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'desc')
        ->limit(30)
        ->get();
    }
}
