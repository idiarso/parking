@props([
    'kendaraan' => null,
    'aksi' => true
])

@php
    $jenisWarna = [
        'motor' => 'primary',
        'mobil' => 'success'
    ];

    $jenisIcon = [
        'motor' => 'fa-motorcycle',
        'mobil' => 'fa-car'
    ];

    $statusWarna = [
        'parkir' => 'warning',
        'keluar' => 'success',
        'pending' => 'secondary'
    ];

    $selectedWarna = $jenisWarna[$kendaraan->jenis_kendaraan] ?? 'info';
    $selectedIcon = $jenisIcon[$kendaraan->jenis_kendaraan] ?? 'fa-question';
    $statusWarnaCard = $statusWarna[$kendaraan->status] ?? 'primary';
@endphp

<div class="col-md-4 mb-4">
    <div class="card border-left-{{ $selectedWarna }} shadow h-100">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-{{ $selectedWarna }} text-uppercase mb-1">
                        <i class="fas {{ $selectedIcon }} me-2"></i>
                        {{ $kendaraan->plat_nomor }}
                    </div>
                    <div class="h5 mb-2 font-weight-bold text-gray-800">
                        {{ ucfirst($kendaraan->jenis_kendaraan) }}
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-{{ $statusWarnaCard }}">
                            {{ ucfirst($kendaraan->status) }}
                        </span>
                    </div>
                    <div class="small text-gray-500">
                        <i class="fas fa-parking me-2"></i>
                        Slot: {{ $kendaraan->slot->nomor_slot }}
                    </div>
                </div>
                <div class="col-auto">
                    <div class="h2 text-gray-300">
                        <i class="fas {{ $selectedIcon }} fa-2x"></i>
                    </div>
                </div>
            </div>
            
            @if($aksi)
            <hr class="my-2">
            <div class="d-flex justify-content-between">
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    {{ $kendaraan->waktu_masuk->diffForHumans() }}
                </small>
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-info" onclick="detailKendaraan({{ $kendaraan->id }})">
                        <i class="fas fa-eye"></i>
                    </button>
                    @if($kendaraan->status === 'parkir')
                    <button class="btn btn-warning" onclick="editKendaraan({{ $kendaraan->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" onclick="keluarkanKendaraan({{ $kendaraan->id }})">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function detailKendaraan(id) {
    Swal.fire({
        title: 'Detail Kendaraan',
        html: `
            <div class="text-start">
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Plat Nomor:</strong> B 1234 XYZ</p>
                <p><strong>Jenis:</strong> Motor</p>
                <p><strong>Waktu Masuk:</strong> 2024-01-28 10:30</p>
                <p><strong>Durasi Parkir:</strong> 3 Jam</p>
                <p><strong>Status:</strong> Parkir</p>
            </div>
        `,
        icon: 'info'
    });
}

function editKendaraan(id) {
    Swal.fire({
        title: 'Edit Kendaraan',
        html: `
            <form>
                <div class="mb-3">
                    <label class="form-label">Plat Nomor</label>
                    <input type="text" class="form-control" value="B 1234 XYZ">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kendaraan</label>
                    <select class="form-select">
                        <option selected>Motor</option>
                        <option>Mobil</option>
                    </select>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal'
    });
}

function keluarkanKendaraan(id) {
    Swal.fire({
        title: 'Keluarkan Kendaraan',
        text: 'Apakah Anda yakin ingin mengeluarkan kendaraan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Keluarkan!'
    });
}
</script>
@endpush
