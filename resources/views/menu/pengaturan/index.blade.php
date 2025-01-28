@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-cogs me-2"></i>Pengaturan Sistem
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-money-bill-wave me-2"></i>Tarif Parkir
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jenis Kendaraan</th>
                                <th>Tarif/Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tarifParkir as $tarif)
                            <tr>
                                <td>
                                    <i class="fas 
                                        @if($tarif->jenis_kendaraan == 'motor') fa-motorcycle 
                                        @else fa-car 
                                        @endif me-2"></i>
                                    {{ ucfirst($tarif->jenis_kendaraan) }}
                                </td>
                                <td>Rp. {{ number_format($tarif->tarif_perjam, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-tarif" 
                                            data-id="{{ $tarif->id }}"
                                            data-jenis="{{ $tarif->jenis_kendaraan }}"
                                            data-tarif="{{ $tarif->tarif_perjam }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Manajemen Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengguna as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge 
                                        @if($user->role == 'admin') bg-danger 
                                        @elseif($user->role == 'operator') bg-warning 
                                        @else bg-secondary 
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info edit-user" 
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger hapus-user" 
                                                data-id="{{ $user->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-primary tambah-user">
                        <i class="fas fa-plus me-2"></i>Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Tarif -->
<div class="modal fade" id="editTarifModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Tarif Parkir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditTarif">
                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan</label>
                        <input type="text" class="form-control" id="editJenisKendaraan" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarif per Jam</label>
                        <input type="number" class="form-control" id="editTarifPerjam">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit User -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formUser">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" id="userName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peran</label>
                        <select class="form-select" id="userRole" required>
                            <option value="operator">Operator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="userPassword">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                    <button type="submit" class="btn btn-primary" id="simpanUser">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Edit Tarif
    $('.edit-tarif').click(function() {
        const id = $(this).data('id');
        const jenis = $(this).data('jenis');
        const tarif = $(this).data('tarif');

        $('#editJenisKendaraan').val(jenis);
        $('#editTarifPerjam').val(tarif);

        $('#editTarifModal').modal('show');
    });

    // Tambah/Edit User
    $('.tambah-user').click(function() {
        $('#userModalTitle').text('Tambah Pengguna');
        $('#formUser')[0].reset();
        $('#userPassword').attr('required', true);
        $('#userModal').modal('show');
    });

    $('.edit-user').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        const role = $(this).data('role');

        $('#userModalTitle').text('Edit Pengguna');
        $('#userName').val(name);
        $('#userEmail').val(email);
        $('#userRole').val(role);
        $('#userPassword').removeAttr('required');
        $('#userModal').modal('show');
    });

    // Hapus User
    $('.hapus-user').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus pengguna ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implementasi hapus user
            }
        });
    });
});
</script>
@endsection
