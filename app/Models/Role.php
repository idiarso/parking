<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi'];

    // Konstanta Role
    const ROLES = [
        'admin' => 'Administrator Sistem',
        'operator' => 'Operator Parkir',
        'manajer' => 'Manajer Parkir',
        'teknisi' => 'Teknisi Infrastruktur',
        'security' => 'Petugas Keamanan'
    ];

    // Relasi dengan Permission
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    // Relasi dengan User
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Scope untuk filter role
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama', $nama);
    }

    // Metode untuk menambahkan permission
    public function tambahPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::firstOrCreate(['nama' => $permission]);
        }

        $this->permissions()->syncWithoutDetaching($permission);
        return $this;
    }

    // Statistik role
    public static function statistikRole()
    {
        return self::withCount('users')->get();
    }
}
