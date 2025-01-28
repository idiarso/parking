<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Sinkronisasi Permission Default
        Permission::sinkronisasiDefault();

        // Buat Role Default
        $roles = [
            'admin' => [
                'nama' => 'Administrator Sistem',
                'permissions' => [
                    'parkir_view', 'parkir_create', 'parkir_update', 'parkir_delete',
                    'pembayaran_view', 'pembayaran_proses', 'pembayaran_refund',
                    'perangkat_view', 'perangkat_create', 'perangkat_update', 'perangkat_maintenance',
                    'user_view', 'user_create', 'user_update', 'user_delete',
                    'laporan_view', 'laporan_generate',
                    'sistem_config'
                ]
            ],
            'operator' => [
                'nama' => 'Operator Parkir',
                'permissions' => [
                    'parkir_view', 'parkir_create',
                    'pembayaran_view', 'pembayaran_proses'
                ]
            ],
            'manajer' => [
                'nama' => 'Manajer Parkir',
                'permissions' => [
                    'parkir_view', 
                    'pembayaran_view', 
                    'laporan_view', 'laporan_generate'
                ]
            ],
            'teknisi' => [
                'nama' => 'Teknisi Infrastruktur',
                'permissions' => [
                    'perangkat_view', 'perangkat_update', 'perangkat_maintenance'
                ]
            ],
            'security' => [
                'nama' => 'Petugas Keamanan',
                'permissions' => [
                    'parkir_view'
                ]
            ]
        ];

        foreach ($roles as $kode => $detail) {
            $role = Role::firstOrCreate(
                ['nama' => $kode],
                ['deskripsi' => $detail['nama']]
            );

            // Tambahkan permissions ke role
            foreach ($detail['permissions'] as $permissionNama) {
                $permission = Permission::where('nama', $permissionNama)->first();
                if ($permission) {
                    $role->tambahPermission($permission);
                }
            }
        }

        // Buat User Default
        $defaultUsers = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@sistemparkir.com',
                'password' => 'AdminParkir2024!',
                'role' => 'admin'
            ],
            [
                'name' => 'Operator Parkir',
                'email' => 'operator@sistemparki.com',
                'password' => 'OperatorParkir2024!',
                'role' => 'operator'
            ],
            [
                'name' => 'Manajer Parkir',
                'email' => 'manajer@sistemparki.com',
                'password' => 'ManajerParkir2024!',
                'role' => 'manajer'
            ]
        ];

        foreach ($defaultUsers as $userData) {
            $role = Role::where('nama', $userData['role'])->first();
            
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'role_id' => $role->id,
                    'email_verified_at' => now()
                ]
            );
        }
    }
}
