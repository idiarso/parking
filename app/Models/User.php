<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Tambahkan relasi dengan Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Metode untuk mengecek role
    public function hasRole($role)
    {
        return $this->role->nama === $role;
    }

    // Metode untuk mengecek permission
    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('nama', $permission);
    }

    // Metode untuk mendapatkan semua permission
    public function getPermissionsAttribute()
    {
        return $this->role->permissions->pluck('nama');
    }

    // Scope untuk filter berdasarkan role
    public function scopeByRole($query, $role)
    {
        return $query->whereHas('role', function($q) use ($role) {
            $q->where('nama', $role);
        });
    }

    // Statistik aktivitas pengguna
    public function aktivitasTerakhir()
    {
        return [
            'login_terakhir' => $this->last_login_at,
            'ip_terakhir' => $this->last_login_ip,
            'total_login' => $this->login_count
        ];
    }

    // Override metode default untuk menambahkan informasi login
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
        $this->last_login_at = now();
        $this->last_login_ip = request()->ip();
        $this->login_count += 1;
        $this->save();
    }

    // Metode untuk reset login attempts
    public function resetLoginAttempts()
    {
        $this->login_attempts = 0;
        $this->save();
    }

    // Metode untuk mencatat login yang gagal
    public function incrementLoginAttempts()
    {
        $this->login_attempts += 1;
        $this->save();

        // Nonaktifkan akun sementara jika terlalu banyak percobaan
        if ($this->login_attempts >= 5) {
            $this->is_locked = true;
            $this->locked_until = now()->addMinutes(30);
            $this->save();
        }
    }
}
