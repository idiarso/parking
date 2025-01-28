<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'kategori'];

    // Konstanta Permission
    const PERMISSIONS = [
        // Manajemen Parkir
        'parkir_view' => ['kategori' => 'Parkir', 'deskripsi' => 'Lihat Data Parkir'],
        'parkir_create' => ['kategori' => 'Parkir', 'deskripsi' => 'Tambah Data Parkir'],
        'parkir_update' => ['kategori' => 'Parkir', 'deskripsi' => 'Ubah Data Parkir'],
        'parkir_delete' => ['kategori' => 'Parkir', 'deskripsi' => 'Hapus Data Parkir'],

        // Manajemen Pembayaran
        'pembayaran_view' => ['kategori' => 'Pembayaran', 'deskripsi' => 'Lihat Transaksi Pembayaran'],
        'pembayaran_proses' => ['kategori' => 'Pembayaran', 'deskripsi' => 'Proses Pembayaran'],
        'pembayaran_refund' => ['kategori' => 'Pembayaran', 'deskripsi' => 'Refund Pembayaran'],

        // Manajemen Perangkat
        'perangkat_view' => ['kategori' => 'Perangkat', 'deskripsi' => 'Lihat Perangkat'],
        'perangkat_create' => ['kategori' => 'Perangkat', 'deskripsi' => 'Tambah Perangkat'],
        'perangkat_update' => ['kategori' => 'Perangkat', 'deskripsi' => 'Update Perangkat'],
        'perangkat_maintenance' => ['kategori' => 'Perangkat', 'deskripsi' => 'Pemeliharaan Perangkat'],

        // Manajemen Pengguna
        'user_view' => ['kategori' => 'Pengguna', 'deskripsi' => 'Lihat Pengguna'],
        'user_create' => ['kategori' => 'Pengguna', 'deskripsi' => 'Tambah Pengguna'],
        'user_update' => ['kategori' => 'Pengguna', 'deskripsi' => 'Update Pengguna'],
        'user_delete' => ['kategori' => 'Pengguna', 'deskripsi' => 'Hapus Pengguna'],

        // Laporan dan Analitik
        'laporan_view' => ['kategori' => 'Laporan', 'deskripsi' => 'Lihat Laporan'],
        'laporan_generate' => ['kategori' => 'Laporan', 'deskripsi' => 'Generate Laporan'],

        // Konfigurasi Sistem
        'sistem_config' => ['kategori' => 'Sistem', 'deskripsi' => 'Konfigurasi Sistem']
    ];

    // Relasi dengan Role
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    // Scope untuk filter berdasarkan kategori
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Metode untuk sinkronisasi permission
    public static function sinkronisasiDefault()
    {
        foreach (self::PERMISSIONS as $kode => $detail) {
            self::firstOrCreate(
                ['nama' => $kode],
                [
                    'kategori' => $detail['kategori'],
                    'deskripsi' => $detail['deskripsi']
                ]
            );
        }
    }

    // Statistik permission
    public static function statistikPermission()
    {
        return self::withCount('roles')->get();
    }
}
