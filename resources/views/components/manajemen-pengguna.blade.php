@props([
    'pengguna' => [],
    'roles' => ['admin', 'operator', 'supervisor', 'security']
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-users-cog me-2"></i>Manajemen Pengguna
        </h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">
            <i class="fas fa-plus me-2"></i>Tambah Pengguna
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="tabelManajemenPengguna">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengguna as $user)
                    <tr>
                        <td>
                            <img src="{{ $user->foto_profil }}" 
                                 class="rounded-circle" 
                                 style="width: 40px; height: 40px; object-fit: cover;">
                        </td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge 
                                @switch($user->role)
                                    @case('admin') bg-danger @break
                                    @case('operator') bg-primary @break
                                    @case('supervisor') bg-warning @break
                                    @case('security') bg-success @break
                                    @default bg-secondary @endswitch
                            ">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge 
                                {{ $user->aktif ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm" onclick="detailPengguna({{ $user->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
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

{{-- Modal Tambah Pengguna --}}
<div class="modal fade" id="tambahPenggunaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahPengguna">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" name="foto_profil" accept="image/*">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Simpan Pengguna
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabel = $('#tabelManajemenPengguna').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });

    const formTambahPengguna = document.getElementById('formTambahPengguna');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.querySelector('input[name="password"]');

    togglePasswordBtn.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    formTambahPengguna.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Tambah Pengguna',
            text: 'Apakah Anda yakin ingin menambahkan pengguna baru?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tambah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Pengguna Ditambahkan',
                    text: 'Pengguna baru berhasil disimpan',
                    icon: 'success'
                });
            }
        });
    });
});

function detailPengguna(id) {
    Swal.fire({
        title: 'Detail Pengguna',
        html: `
            <div class="text-start">
                <p><strong>ID Pengguna:</strong> ${id}</p>
                <p><strong>Nama:</strong> Admin Sistem</p>
                <p><strong>Email:</strong> admin@sistemparkir.com</p>
                <p><strong>Role:</strong> Administrator</p>
                <p><strong>Terakhir Login:</strong> 2024-01-28 10:30</p>
            </div>
        `,
        icon: 'info'
    });
}

function editPengguna(id) {
    Swal.fire({
        title: 'Edit Pengguna',
        html: `
            <form>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" value="Admin Sistem">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="admin@sistemparki.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select">
                        <option selected>Administrator</option>
                        <option>Operator</option>
                        <option>Supervisor</option>
                    </select>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan Perubahan',
        cancelButtonText: 'Batal'
    });
}

function hapusPengguna(id) {
    Swal.fire({
        title: 'Hapus Pengguna',
        text: 'Apakah Anda yakin ingin menghapus pengguna ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Pengguna Dihapus',
                text: 'Pengguna berhasil dihapus dari sistem',
                icon: 'success'
            });
        }
    });
}
</script>
@endpush
