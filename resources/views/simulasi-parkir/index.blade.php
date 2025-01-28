@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-chart-line me-2"></i>Simulasi dan Prediksi Parkir
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Simulasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistikSimulasi['total_simulasi'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach($statistikSimulasi['per_status'] as $status)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $loop->first ? 'success' : 'warning' }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $loop->first ? 'success' : 'warning' }} text-uppercase mb-1">
                                {{ $status->status_simulasi }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $status->total }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-{{ $loop->first ? 'check-circle' : 'spinner' }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Simulasi Terakhir
                    </h6>
                    <button class="btn btn-primary btn-sm" id="buatSimulasi">
                        <i class="fas fa-plus me-1"></i>Buat Simulasi Baru
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tabelSimulasi">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Total Slot</th>
                                    <th>Prediksi Kendaraan</th>
                                    <th>Prediksi Pendapatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($simulasiTerakhir as $simulasi)
                                <tr>
                                    <td>{{ $simulasi->tanggal_simulasi->format('d M Y') }}</td>
                                    <td>{{ $simulasi->total_slot }}</td>
                                    <td>{{ $simulasi->prediksi_kendaraan_masuk ?? '-' }}</td>
                                    <td>Rp {{ number_format($simulasi->prediksi_pendapatan ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($simulasi->status_simulasi == 'selesai') bg-success
                                            @elseif($simulasi->status_simulasi == 'draft') bg-secondary
                                            @else bg-warning
                                            @endif">
                                            {{ $simulasi->status_simulasi }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info detail-simulasi" data-id="{{ $simulasi->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($simulasi->status_simulasi == 'draft')
                                        <button class="btn btn-sm btn-primary jalankan-simulasi" data-id="{{ $simulasi->id }}">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Statistik Skenario
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="skenarioChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($statistikSkenario['per_status'] as $status)
                        <span class="me-2">
                            <i class="fas fa-circle 
                                @if($status->status == 'selesai') text-success
                                @elseif($status->status == 'draft') text-secondary
                                @else text-warning
                                @endif"></i> 
                            {{ $status->status }} ({{ $status->total }})
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buat Simulasi -->
<div class="modal fade" id="modalSimulasi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Simulasi Parkir Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSimulasi">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Simulasi</label>
                            <input type="date" class="form-control" name="tanggal" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Slot Parkir</label>
                            <input type="number" class="form-control" name="total_slot" value="100" min="10" max="500">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" value="06:00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" value="22:00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kapasitas Motor</label>
                            <input type="number" class="form-control" name="kapasitas_motor" value="60" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kapasitas Mobil</label>
                            <input type="number" class="form-control" name="kapasitas_mobil" value="40" min="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanSimulasi">Buat Simulasi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Simulasi -->
<div class="modal fade" id="modalDetailSimulasi" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Simulasi Parkir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi Simulasi</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th>Tanggal</th>
                                <td id="detailTanggal"></td>
                            </tr>
                            <tr>
                                <th>Total Slot</th>
                                <td id="detailTotalSlot"></td>
                            </tr>
                            <tr>
                                <th>Prediksi Kendaraan Masuk</th>
                                <td id="detailKendaraanMasuk"></td>
                            </tr>
                            <tr>
                                <th>Prediksi Pendapatan</th>
                                <td id="detailPendapatan"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Skenario</h6>
                        <div id="daftarSkenario"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi DataTable
    $('#tabelSimulasi').DataTable({
        responsive: true,
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ entri'
        }
    });

    // Grafik Skenario
    const skenarioChart = new Chart(document.getElementById('skenarioChart'), {
        type: 'pie',
        data: {
            labels: [
                @foreach($statistikSkenario['per_status'] as $status)
                    '{{ $status->status }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($statistikSkenario['per_status'] as $status)
                        {{ $status->total }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)', 
                    'rgba(28, 200, 138, 0.8)', 
                    'rgba(246, 194, 62, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Buat Simulasi
    $('#buatSimulasi').on('click', function() {
        $('#modalSimulasi').modal('show');
    });

    $('#simpanSimulasi').on('click', function() {
        $.ajax({
            url: '{{ route("simulasi-parkir.buat") }}',
            method: 'POST',
            data: $('#formSimulasi').serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Simulasi Berhasil Dibuat',
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

    // Jalankan Simulasi
    $('.jalankan-simulasi').on('click', function() {
        let simulasiId = $(this).data('id');

        Swal.fire({
            title: 'Jalankan Simulasi',
            text: 'Anda yakin ingin menjalankan simulasi ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Jalankan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/simulasi-parkir/${simulasiId}/jalankan`,
                    method: 'POST',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Simulasi Berhasil Dijalankan',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    });

    // Detail Simulasi
    $('.detail-simulasi').on('click', function() {
        let simulasiId = $(this).data('id');

        $.ajax({
            url: `/simulasi-parkir/${simulasiId}`,
            method: 'GET',
            success: function(response) {
                let simulasi = response.simulasi;
                
                $('#detailTanggal').text(simulasi.tanggal_simulasi);
                $('#detailTotalSlot').text(simulasi.total_slot);
                $('#detailKendaraanMasuk').text(simulasi.prediksi_kendaraan_masuk || '-');
                $('#detailPendapatan').text('Rp ' + 
                    (simulasi.prediksi_pendapatan 
                        ? new Intl.NumberFormat('id-ID').format(simulasi.prediksi_pendapatan) 
                        : '-')
                );

                // Tampilkan skenario
                let skenarioHtml = '';
                if (simulasi.skenario && simulasi.skenario.length > 0) {
                    simulasi.skenario.forEach(function(skenario) {
                        skenarioHtml += `
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h6 class="card-title">${skenario.nama}</h6>
                                    <p class="card-text">${skenario.deskripsi || '-'}</p>
                                    <span class="badge ${
                                        skenario.status === 'selesai' 
                                        ? 'bg-success' 
                                        : (skenario.status === 'draft' 
                                            ? 'bg-secondary' 
                                            : 'bg-warning')
                                    }">
                                        ${skenario.status}
                                    </span>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    skenarioHtml = '<p class="text-muted">Belum ada skenario</p>';
                }
                $('#daftarSkenario').html(skenarioHtml);

                $('#modalDetailSimulasi').modal('show');
            }
        });
    });
});
</script>
@endpush
