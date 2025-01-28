@props([
    'judul' => 'Statistik',
    'nilai' => '0',
    'icon' => 'fa-chart-bar',
    'warna' => 'primary',
    'keterangan' => '',
    'perubahan' => null
])

@php
    $warnaClass = [
        'primary' => 'border-left-primary',
        'success' => 'border-left-success', 
        'warning' => 'border-left-warning',
        'danger' => 'border-left-danger',
        'info' => 'border-left-info'
    ];

    $warnaIcon = [
        'primary' => 'text-primary',
        'success' => 'text-success', 
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'info' => 'text-info'
    ];

    $selectedWarna = $warnaClass[$warna] ?? 'border-left-primary';
    $selectedWarnaIcon = $warnaIcon[$warna] ?? 'text-primary';
@endphp

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card {{ $selectedWarna }} shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold {{ $selectedWarnaIcon }} text-uppercase mb-1">
                        {{ $judul }}
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $nilai }}
                    </div>
                    @if($keterangan)
                        <div class="text-xs text-muted mt-1">
                            {{ $keterangan }}
                        </div>
                    @endif
                    @if($perubahan !== null)
                        <div class="mt-2 {{ $perubahan >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas {{ $perubahan >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            {{ abs($perubahan) }}% dari bulan lalu
                        </div>
                    @endif
                </div>
                <div class="col-auto">
                    <i class="fas {{ $icon }} fa-2x {{ $selectedWarnaIcon }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>
