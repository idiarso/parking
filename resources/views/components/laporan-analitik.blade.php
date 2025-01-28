@props([
    'pendapatanBulanan' => [],
    'okupasiHarian' => [],
    'jenisKendaraan' => [],
    'waktuPuncak' => []
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-line me-2"></i>Laporan dan Analitik Parkir
        </h6>
        <div class="btn-group" role="group">
            <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="harian">Harian</button>
            <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="mingguan">Mingguan</button>
            <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="bulanan">Bulanan</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-money-bill-wave me-2"></i>Pendapatan
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pendapatanChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-parking me-2"></i>Okupasi Slot Parkir
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="okupasiChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-motorcycle me-2"></i>Jenis Kendaraan
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="jenisKendaraanChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clock me-2"></i>Waktu Puncak
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Jumlah Kendaraan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($waktuPuncak as $jam => $jumlah)
                                <tr>
                                    <td>{{ $jam }}:00</td>
                                    <td>{{ $jumlah }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pendapatanCtx = document.getElementById('pendapatanChart').getContext('2d');
    const okupasiCtx = document.getElementById('okupasiChart').getContext('2d');
    const jenisKendaraanCtx = document.getElementById('jenisKendaraanChart').getContext('2d');

    // Pendapatan Chart
    new Chart(pendapatanCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($pendapatanBulanan)),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json(array_values($pendapatanBulanan)),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Okupasi Chart
    new Chart(okupasiCtx, {
        type: 'line',
        data: {
            labels: @json(array_keys($okupasiHarian)),
            datasets: [{
                label: 'Okupasi Slot (%)',
                data: @json(array_values($okupasiHarian)),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Jenis Kendaraan Chart
    new Chart(jenisKendaraanCtx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($jenisKendaraan)),
            datasets: [{
                data: @json(array_values($jenisKendaraan)),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });

    // Filter Button Logic
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        const filter = $(this).data('filter');
        
        // Implementasi filter data (akan dilakukan di backend)
        $.ajax({
            url: '{{ route("laporan.filter") }}',
            method: 'GET',
            data: { filter: filter },
            success: function(response) {
                // Update chart dengan data baru
                console.log(response);
            }
        });
    });
});
</script>
@endpush
