@props([
    'statistik' => [],
    'periode' => 'Bulan Ini'
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-pie me-2"></i>Ringkasan Statistik {{ $periode }}
        </h6>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                {{ $periode }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" data-periode="bulan_ini">Bulan Ini</a></li>
                <li><a class="dropdown-item" href="#" data-periode="triwulan">Triwulan</a></li>
                <li><a class="dropdown-item" href="#" data-periode="tahun_ini">Tahun Ini</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($statistik as $item)
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-{{ $item['warna'] }} shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-{{ $item['warna'] }} text-uppercase mb-1">
                                        {{ $item['judul'] }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $item['nilai'] }}
                                    </div>
                                    @if(isset($item['perubahan']))
                                        <div class="mt-2 {{ $item['perubahan'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            <i class="fas {{ $item['perubahan'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                                            {{ abs($item['perubahan']) }}% dari periode sebelumnya
                                        </div>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <i class="fas {{ $item['icon'] }} fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Grafik Perbandingan</h5>
                        <div class="chart-container" style="height:300px;">
                            <canvas id="statistikGrafik"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statistikData = @json($statistik);
    const ctx = document.getElementById('statistikGrafik').getContext('2d');

    const labels = statistikData.map(item => item.judul);
    const values = statistikData.map(item => parseFloat(item.nilai.replace(/[^0-9.-]+/g,"")));
    const colors = statistikData.map(item => `rgba(${Math.random()*255},${Math.random()*255},${Math.random()*255},0.6)`);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Statistik',
                data: values,
                backgroundColor: colors,
                borderWidth: 1
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
    });

    // Dropdown periode
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const periode = this.getAttribute('data-periode');
            
            Swal.fire({
                title: 'Memperbarui Statistik',
                text: `Menampilkan statistik periode ${periode}`,
                icon: 'info',
                timer: 1500
            });
        });
    });
});
</script>
@endpush
