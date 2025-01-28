<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ManajemenPenggunaController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();

        $statistikPengguna = [
            'total_pengguna' => User::count(),
            'per_role' => User::selectRaw('role_id, COUNT(*) as total')
                        ->groupBy('role_id')
                        ->with('role')
                        ->get()
        ];

        return view('manajemen-pengguna.index', compact(
            'users', 
            'roles', 
            'statistikPengguna'
        ));
    }

    public function tambahPengguna(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Pengguna berhasil ditambahkan'
        ]);
    }

    public function updatePengguna(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes', 
                'required', 
                'email', 
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|min:8|confirmed',
            'role_id' => 'sometimes|required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Update password hanya jika diisi
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Pengguna berhasil diperbarui'
        ]);
    }

    public function hapusPengguna($userId)
    {
        $user = User::findOrFail($userId);
        
        // Cegah penghapusan user terakhir dengan role admin
        $adminCount = User::whereHas('role', function($q) {
            $q->where('nama', 'admin');
        })->count();

        if ($adminCount <= 1 && $user->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat menghapus admin terakhir'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil dihapus'
        ]);
    }

    public function resetPassword($userId)
    {
        $user = User::findOrFail($userId);
        
        // Generate password acak
        $passwordBaru = 'Parkir_' . strtoupper(substr(uniqid(), -6));
        
        $user->update([
            'password' => Hash::make($passwordBaru),
            'login_attempts' => 0,
            'is_locked' => false,
            'locked_until' => null
        ]);

        return response()->json([
            'status' => 'success',
            'password_baru' => $passwordBaru,
            'message' => 'Password berhasil direset'
        ]);
    }

    public function riwayatLogin($userId)
    {
        $user = User::findOrFail($userId);
        
        return response()->json([
            'status' => 'success',
            'aktivitas_terakhir' => $user->aktivitasTerakhir()
        ]);
    }
}
