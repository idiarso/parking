<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id', 
        'aktivitas', 
        'deskripsi', 
        'ip_address', 
        'user_agent'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Method untuk mencatat log aktivitas
    public static function catat($aktivitas, $deskripsi = null)
    {
        return self::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    // Scope untuk filter aktivitas
    public function scopeFilterAktivitas($query, $aktivitas = null)
    {
        if ($aktivitas) {
            return $query->where('aktivitas', 'like', "%{$aktivitas}%");
        }
        return $query;
    }
}
