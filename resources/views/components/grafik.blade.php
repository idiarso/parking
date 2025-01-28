@props([
    'id' => 'grafikContainer',
    'judul' => 'Grafik',
    'tipe' => 'line',
    'label' => [],
    'data' => [],
    'warna' => 'primary'
])

@php
    $warnaGrafik = [
        'primary' => 'rgb(78, 115, 223)',
        'success' => 'rgb(28, 200, 138)',
        'warning' => 'rgb(246, 194, 62)',
        'danger' => 'rgb(231, 74, 59)',
        'info' => 'rgb(36, 185, 193)'
    ];

    $selectedWarna = $warnaGrafik[$warna] ?? 'rgb(78, 115, 223)';
@endphp

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-{{ $warna }}">{{ $judul }}</h6>
    </div>
    <div class="card-body">
        <div class="chart-container position-relative" style="height:300px;">
            <canvas id="{{ $id }}"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id }}').getContext('2d');
    
    const chartConfig = {
        type: '{{ $tipe }}',
        data: {
            labels: @json($label),
            datasets: [{
                label: '{{ $judul }}',
                data: @json($data),
                backgroundColor: '{{ $selectedWarna }}',
                borderColor: '{{ $selectedWarna }}',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    new Chart(ctx, chartConfig);
});
</script>
@endpush
