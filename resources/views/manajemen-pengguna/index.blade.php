@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-users me-2"></i>Manajemen Pengguna
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pengguna
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistikPengguna['total_pengguna'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach($statistikPengguna['per_role'] as $roleStats)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-{{ $loop->first ? 'success' : 'info' }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $loop->first ? 'success' : 'info' }} text-uppercase mb-1">
                                {{ $roleStats->role->nama }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $roleStats->total }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-{{ $loop->first ? 'user-shield' : 'user-tag' }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Daftar Pengguna
            </h6>
            <button class="btn btn-primary btn-sm" id="tambahPengguna">
                <i class="fas fa-plus me-1"></i>Tambah Pengguna
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="tabelPengguna">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Terakhir Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge 
                                    @if($user->role->nama == 'admin') bg-danger
                                    @elseif($user->role->nama == 'manajer') bg-warning
                                    @else bg-info
                                    @endif">
                                    {{ ucfirst($user->role->nama) }}
                                </span>
                            </td>
                            <td>
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum Login' }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-info edit-pengguna" 
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}"
                                            data-role="{{ $user->role_id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning reset-password" 
                                            data-id="{{ $user->id }}">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger hapus-pengguna" 
                                            data-id="{{ $user->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Pengguna -->
<div class="modal fade" id="modalPengguna" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulModal">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formPengguna">
                    <input type="hidden" name="user_id" id="userId">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role_id" required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="passwordSection">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                    <div class="mb-3" id="passwordConfirmSection">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanPengguna">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#tabelPengguna').DataTable({
        responsive: true,
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ entri'
        }
    });

    // Tambah Pengguna
    $('#tambahPengguna').on('click', function() {
        $('#modalPengguna').modal('show');
        $('#judulModal').text('Tambah Pengguna');
        $('#formPengguna')[0].reset();
        $('#userId').val('');
        $('#passwordSection, #passwordConfirmSection').show();
    });

    // Edit Pengguna
    $('.edit-pengguna').on('click', function() {
        let userId = $(this).data('id');
        let name = $(this).data('name');
        let email = $(this).data('email');
        let roleId = $(this).data('role');

        $('#modalPengguna').modal('show');
        $('#judulModal').text('Edit Pengguna');
        $('#userId').val(userId);
        $('input[name="name"]').val(name);
        $('input[name="email"]').val(email);
        $('select[name="role_id"]').val(roleId);
        $('#passwordSection, #passwordConfirmSection').hide();
    });

    // Simpan Pengguna
    $('#simpanPengguna').on('click', function() {
        let userId = $('#userId').val();
        let url = userId ? `/manajemen-pengguna/${userId}` : '/manajemen-pengguna/tambah';
        let method = userId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $('#formPengguna').serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = Object.values(errors).flat().join('\n');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage
                });
            }
        });
    });

    // Reset Password
    $('.reset-password').on('click', function() {
        let userId = $(this).data('id');

        Swal.fire({
            title: 'Reset Password',
            text: 'Anda yakin ingin mereset password pengguna ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/manajemen-pengguna/${userId}/reset-password`,
                    method: 'POST',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Direset',
                            html: `Password baru: <strong>${response.password_baru}</strong><br>Harap segera ganti password`
                        });
                    }
                });
            }
        });
    });

    // Hapus Pengguna
    $('.hapus-pengguna').on('click', function() {
        let userId = $(this).data('id');

        Swal.fire({
            title: 'Hapus Pengguna',
            text: 'Anda yakin ingin menghapus pengguna ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/manajemen-pengguna/${userId}`,
                    method: 'DELETE',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON.message
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
