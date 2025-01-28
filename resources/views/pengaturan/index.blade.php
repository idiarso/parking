@extends('layouts.app')

@section('styles')
<style>
    .setting-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .setting-card:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-cogs"></i> Pengaturan Sistem
            </h1>
        </div>
    </div>

    <div class="row">
        <!-- Manajemen Tarif -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card setting-card shadow" data-bs-toggle="modal" data-bs-target="#tarifModal">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Manajemen Tarif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Atur Tarif Parkir
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manajemen Pengguna -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card setting-card shadow" data-bs-toggle="modal" data-bs-target="#penggunaModal">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Manajemen Pengguna
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalPengguna }} Akun
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konfigurasi Sistem -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card setting-card shadow" data-bs-toggle="modal" data-bs-target="#sistemModal">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Konfigurasi Sistem
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Pengaturan Lanjutan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup & Restore -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-database"></i> Backup & Restore
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Backup Database</h5>
                            <p>Simpan salinan data sistem parkir Anda.</p>
                            <button class="btn btn-primary" id="backupDatabase">
                                <i class="fas fa-download"></i> Backup Sekarang
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h5>Restore Database</h5>
                            <p>Pulihkan data dari cadangan sebelumnya.</p>
                            <div class="input-group">
                                <input type="file" class="form-control" id="restoreFile" accept=".sql,.zip">
                                <button class="btn btn-warning" id="restoreDatabase">
                                    <i class="fas fa-upload"></i> Restore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tarif -->
<div class="modal fade" id="tarifModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manajemen Tarif Parkir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="tarifForm">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Tarif Motor</h6>
                            <div class="mb-3">
                                <label>Tarif 1 Jam Pertama</label>
                                <input type="number" class="form-control" name="tarif_motor_1jam" value="{{ $tarifMotor->tarif_per_jam }}">
                            </div>
                            <div class="mb-3">
                                <label>Tarif Jam Berikutnya</label>
                                <input type="number" class="form-control" name="tarif_motor_lanjut" value="{{ $tarifMotor->tarif_per_jam_berikutnya }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Tarif Mobil</h6>
                            <div class="mb-3">
                                <label>Tarif 1 Jam Pertama</label>
                                <input type="number" class="form-control" name="tarif_mobil_1jam" value="{{ $tarifMobil->tarif_per_jam }}">
                            </div>
                            <div class="mb-3">
                                <label>Tarif Jam Berikutnya</label>
                                <input type="number" class="form-control" name="tarif_mobil_lanjut" value="{{ $tarifMobil->tarif_per_jam_berikutnya }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanTarif">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pengguna -->
<div class="modal fade" id="penggunaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manajemen Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">
                    <i class="fas fa-plus"></i> Tambah Pengguna
                </button>
                <table class="table table-striped" id="tabelPengguna">
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
                            <td>{{ $user->role }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning btn-sm" onclick="editPengguna({{ $user->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="hapusPengguna({{ $user->id }})">
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

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="tambahPenggunaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="tambahPenggunaForm">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label>Kata Sandi</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label>Peran</label>
                        <select class="form-select" name="role">
                            <option value="admin">Administrator</option>
                            <option value="operator">Operator</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanPengguna">Tambah Pengguna</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfigurasi Sistem -->
<div class="modal fade" id="sistemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfigurasi Sistem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="sistemForm">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Pengaturan Umum</h6>
                            <div class="mb-3">
                                <label>Nama Lokasi Parkir</label>
                                <input type="text" class="form-control" name="nama_lokasi" value="{{ $pengaturan->nama_lokasi }}">
                            </div>
                            <div class="mb-3">
                                <label>Kapasitas Total Slot</label>
                                <input type="number" class="form-control" name="total_slot" value="{{ $pengaturan->total_slot }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Pengaturan Keamanan</h6>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="two_factor" 
                                    {{ $pengaturan->two_factor_enabled ? 'checked' : '' }}>
                                <label class="form-check-label">Aktifkan Two-Factor Authentication</label>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="log_aktivitas" 
                                    {{ $pengaturan->log_aktivitas_enabled ? 'checked' : '' }}>
                                <label class="form-check-label">Aktifkan Log Aktivitas</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanSistem">Simpan Konfigurasi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DataTable Pengguna
    $('#tabelPengguna').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });

    // Backup Database
    $('#backupDatabase').on('click', function() {
        Swal.fire({
            title: 'Backup Database',
            text: 'Sedang membuat backup database...',
            icon: 'info',
            showConfirmButton: false,
            timer: 2000
        });
    });

    // Restore Database
    $('#restoreDatabase').on('click', function() {
        const file = $('#restoreFile')[0].files[0];
        if (!file) {
            Swal.fire({
                title: 'Error',
                text: 'Pilih file backup terlebih dahulu',
                icon: 'error'
            });
            return;
        }

        Swal.fire({
            title: 'Restore Database',
            text: 'Anda yakin ingin memulihkan database?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Pulihkan!'
        });
    });

    // Simpan Tarif
    $('#simpanTarif').on('click', function() {
        Swal.fire({
            title: 'Simpan Tarif',
            text: 'Tarif parkir berhasil diperbarui',
            icon: 'success'
        });
    });

    // Tambah Pengguna
    $('#simpanPengguna').on('click', function() {
        Swal.fire({
            title: 'Tambah Pengguna',
            text: 'Pengguna baru berhasil ditambahkan',
            icon: 'success'
        });
    });

    // Simpan Konfigurasi Sistem
    $('#simpanSistem').on('click', function() {
        Swal.fire({
            title: 'Konfigurasi Sistem',
            text: 'Pengaturan sistem berhasil disimpan',
            icon: 'success'
        });
    });
});

function editPengguna(id) {
    Swal.fire({
        title: 'Edit Pengguna',
        text: 'Fitur edit pengguna akan segera hadir!',
        icon: 'info'
    });
}

function hapusPengguna(id) {
    Swal.fire({
        title: 'Hapus Pengguna',
        text: 'Anda yakin ingin menghapus pengguna ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    });
}
</script>
@endsection
