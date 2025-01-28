<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role admin sudah ada
        $adminRole = Role::firstOrCreate(
            ['nama' => 'admin'],
            [
                'deskripsi' => 'Administrator Sistem dengan akses penuh',
                'is_default' => false
            ]
        );

        // Daftar admin utama
        $adminUsers = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@sistemparkir.local',
                'password' => 'AdminParkir2024!@#',
                'is_active' => true
            ],
            [
                'name' => 'Admin Sistem',
                'email' => 'admin@sistemparkir.local',
                'password' => 'AdminSistem2024!@#',
                'is_active' => true
            ]
        ];

        foreach ($adminUsers as $userData) {
            $existingUser = User::where('email', $userData['email'])->first();
            
            if (!$existingUser) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($userData['password']),
                    'role_id' => $adminRole->id,
                    'is_active' => $userData['is_active'],
                    'remember_token' => Str::random(10),
                    'login_attempts' => 0,
                    'last_login_at' => null,
                    'is_locked' => false,
                    'locked_until' => null
                ]);

                // Tambahkan token untuk API akses
                $user->createToken('admin-token', ['*'])->plainTextToken;
            }
        }

        // Tambahkan log untuk konfirmasi
        \Log::info('Admin users seeded successfully');
    }
}
