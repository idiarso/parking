<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'log_pemeliharaan';

    protected $fillable = [
        'perangkat_id', 
        'deskripsi', 
        'teknisi', 
        'status', 
        'biaya', 
        'suku_cadang_diganti'
    ];

    protected $casts = [
        'suku_cadang_diganti' => 'array',
        'biaya' => 'float'
    ];

    // Status Log Pemeliharaan
    const STATUS = [
        'selesai' => 'Selesai',
        'proses' => 'Sedang Dikerjakan',
        'ditunda' => 'Ditunda'
    ];

    // Relasi dengan Perangkat
    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id');
    }

    // Scope untuk filter status
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeDalamProses($query)
    {
        return $query->where('status', 'proses');
    }

    // Statistik Log Pemeliharaan
    public static function statistikPemeliharaan()
    {
        return [
            'total_log' => self::count(),
            'total_biaya' => self::sum('biaya'),
            'per_status' => self::select('status', \DB::raw('COUNT(*) as total'))
                        ->groupBy('status')
                        ->get(),
            'log_terakhir' => self::with('perangkat')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get()
        ];
    }
}
