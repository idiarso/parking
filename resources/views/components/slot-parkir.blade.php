@props([
    'nomor' => '',
    'status' => 'kosong',
    'tipe' => 'motor',
    'kendaraan' => null
])

@php
    $statusClass = [
        'kosong' => 'bg-success',
        'terisi' => 'bg-danger',
        'maintenance' => 'bg-warning'
    ];

    $tipeIcon = [
        'motor' => 'fa-motorcycle',
        'mobil' => 'fa-car'
    ];

    $selectedStatusClass = $statusClass[$status] ?? 'bg-secondary';
    $selectedTipeIcon = $tipeIcon[$tipe] ?? 'fa-question';
@endphp

<div class="col-md-3 mb-4">
    <div class="card slot-parkir {{ $selectedStatusClass }} text-white shadow">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                        Slot {{ $nomor }} ({{ ucfirst($tipe) }})
                    </div>
                    <div class="h5 mb-0 font-weight-bold">
                        {{ ucfirst($status) }}
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas {{ $selectedTipeIcon }} fa-2x"></i>
                </div>
            </div>
            
            @if($kendaraan)
            <hr class="my-2 border-light">
            <div class="details">
                <small>
                    <strong>Plat:</strong> {{ $kendaraan->plat_nomor }}<br>
                    <strong>Masuk:</strong> {{ $kendaraan->waktu_masuk->format('H:i') }}
                </small>
            </div>
            @endif
        </div>
        <div class="card-footer d-flex justify-content-between">
            <small>
                <i class="fas fa-clock me-1"></i>
                @if($status === 'terisi')
                    {{ $kendaraan ? $kendaraan->waktu_masuk->diffForHumans() : 'Terisi' }}
                @else
                    Tersedia
                @endif
            </small>
            @if($status === 'terisi')
            <button class="btn btn-light btn-sm" onclick="detailKendaraan({{ $kendaraan->id }})">
                <i class="fas fa-eye"></i>
            </button>
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
            </div>
        `,
        icon: 'info'
    });
}
</script>
@endpush
