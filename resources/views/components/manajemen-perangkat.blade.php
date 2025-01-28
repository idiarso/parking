@props([
    'daftarPerangkat' => [],
    'statusInfrastruktur' => [],
    'logPemeliharaan' => []
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-server me-2"></i>Manajemen Perangkat dan Infrastruktur
        </h6>
        <div class="btn-group" role="group">
            <button class="btn btn-sm btn-outline-primary active" data-view="grid">
                <i class="fas fa-th"></i>
            </button>
            <button class="btn btn-sm btn-outline-primary" data-view="list">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-network-wired me-2"></i>Daftar Perangkat
                        </h6>
                    </div>
                    <div class="card-body" id="perangkatContainer">
                        <div class="row">
                            @foreach($daftarPerangkat as $perangkat)
                            <div class="col-md-4 mb-3 perangkat-item">
                                <div class="card 
                                    @if($perangkat->status == 'aktif') border-success 
                                    @elseif($perangkat->status == 'gangguan') border-danger 
                                    @else border-warning 
                                    @endif">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="card-title mb-0">
                                                <i class="{{ $perangkat->icon }} me-2"></i>
                                                {{ $perangkat->nama }}
                                            </h5>
                                            <span class="badge 
                                                @if($perangkat->status == 'aktif') bg-success 
                                                @elseif($perangkat->status == 'gangguan') bg-danger 
                                                @else bg-warning 
                                                @endif">
                                                {{ ucfirst($perangkat->status) }}
                                            </span>
                                        </div>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                SN: {{ $perangkat->serial_number }}
                                            </small>
                                        </p>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar 
                                                @if($perangkat->kesehatan >= 80) bg-success 
                                                @elseif($perangkat->kesehatan >= 50) bg-warning 
                                                @else bg-danger 
                                                @endif" 
                                                role="progressbar" 
                                                style="width: {{ $perangkat->kesehatan }}%"
                                                aria-valuenow="{{ $perangkat->kesehatan }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <small>
                                                Kesehatan: {{ $perangkat->kesehatan }}%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clipboard-list me-2"></i>Log Pemeliharaan
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0" id="logPemeliharaanTable">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Perangkat</th>
                                        <th>Jenis Pemeliharaan</th>
                                        <th>Status</th>
                                        <th>Teknisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logPemeliharaan as $log)
                                    <tr>
                                        <td>{{ $log->tanggal->format('d M Y H:i') }}</td>
                                        <td>{{ $log->nama_perangkat }}</td>
                                        <td>{{ $log->jenis_pemeliharaan }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($log->status == 'selesai') bg-success 
                                                @elseif($log->status == 'proses') bg-warning 
                                                @else bg-danger 
                                                @endif">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->teknisi }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie me-2"></i>Status Infrastruktur
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="infrastrukturChart"></canvas>
                        <div class="mt-3">
                            @foreach($statusInfrastruktur as $status)
                            <div class="d-flex justify-content-between mb-2">
                                <span>
                                    <i class="{{ $status->icon }} me-2"></i>
                                    {{ $status->nama }}
                                </span>
                                <span class="badge 
                                    @if($status->persentase >= 80) bg-success 
                                    @elseif($status->persentase >= 50) bg-warning 
                                    @else bg-danger 
                                    @endif">
                                    {{ $status->persentase }}%
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tools me-2"></i>Tindakan Cepat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action" id="restartSistem">
                                <i class="fas fa-sync me-2"></i>Restart Sistem Parkir
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" id="pemeliharaanTerjadwal">
                                <i class="fas fa-calendar-check me-2"></i>Jadwalkan Pemeliharaan
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" id="laporkanMasalah">
                                <i class="fas fa-exclamation-triangle me-2"></i>Laporkan Masalah
                            </button>
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
    // Grafik Status Infrastruktur
    const infrastrukturCtx = document.getElementById('infrastrukturChart').getContext('2d');
    const infrastrukturChart = new Chart(infrastrukturCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_column($statusInfrastruktur, 'nama')),
            datasets: [{
                data: @json(array_column($statusInfrastruktur, 'persentase')),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',   // Biru
                    'rgba(255, 206, 86, 0.7)',   // Kuning
                    'rgba(255, 99, 132, 0.7)'    // Merah
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Log Pemeliharaan DataTable
    $('#logPemeliharaanTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ entri',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri'
        }
    });

    // Toggle View Mode Perangkat
    const viewButtons = document.querySelectorAll('[data-view]');
    const perangkatContainer = document.getElementById('perangkatContainer');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const viewMode = this.getAttribute('data-view');
            if (viewMode === 'list') {
                perangkatContainer.innerHTML = `
                    <div class="list-group">
                        ${Array.from(document.querySelectorAll('.perangkat-item')).map(item => `
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    ${item.querySelector('.card-title').innerHTML}
                                    <small class="d-block text-muted">
                                        SN: ${item.querySelector('.card-text small').textContent}
                                    </small>
                                </div>
                                <div>
                                    ${item.querySelector('.badge').outerHTML}
                                    <small class="d-block text-muted">
                                        Kesehatan: ${item.querySelector('.progress-bar').getAttribute('aria-valuenow')}%
                                    </small>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
            } else {
                location.reload(); // Kembali ke tampilan grid
            }
        });
    });

    // Tindakan Cepat
    document.getElementById('restartSistem').addEventListener('click', function() {
        Swal.fire({
            title: 'Restart Sistem Parkir',
            text: 'Apakah Anda yakin ingin me-restart seluruh sistem parkir?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Restart',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implementasi restart sistem
                Swal.fire(
                    'Restart Sistem',
                    'Sistem parkir sedang direstart. Mohon tunggu.',
                    'info'
                );
            }
        });
    });

    document.getElementById('pemeliharaanTerjadwal').addEventListener('click', function() {
        Swal.fire({
            title: 'Jadwalkan Pemeliharaan',
            html: `
                <form id="jadwalPemeliharaanForm">
                    <div class="mb-3">
                        <label class="form-label">Pilih Perangkat</label>
                        <select class="form-select" id="perangkatPemeliharaan">
                            ${@json($daftarPerangkat).map(p => `<option value="${p.id}">${p.nama}</option>`).join('')}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pemeliharaan</label>
                        <input type="date" class="form-control" id="tanggalPemeliharaan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan Pemeliharaan</label>
                        <textarea class="form-control" id="catatanPemeliharaan" rows="3"></textarea>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Jadwalkan',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const perangkat = document.getElementById('perangkatPemeliharaan').value;
                const tanggal = document.getElementById('tanggalPemeliharaan').value;
                const catatan = document.getElementById('catatanPemeliharaan').value;

                if (!perangkat || !tanggal) {
                    Swal.showValidationMessage('Harap isi semua field');
                }

                return { perangkat, tanggal, catatan };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Implementasi penjadwalan pemeliharaan
                Swal.fire(
                    'Terjadwal',
                    'Pemeliharaan berhasil dijadwalkan.',
                    'success'
                );
            }
        });
    });

    document.getElementById('laporkanMasalah').addEventListener('click', function() {
        Swal.fire({
            title: 'Laporkan Masalah',
            html: `
                <form id="laporMasalahForm">
                    <div class="mb-3">
                        <label class="form-label">Pilih Perangkat</label>
                        <select class="form-select" id="perangkatMasalah">
                            ${@json($daftarPerangkat).map(p => `<option value="${p.id}">${p.nama}</option>`).join('')}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Masalah</label>
                        <textarea class="form-control" id="deskripsiMasalah" rows="3" placeholder="Jelaskan masalah yang terjadi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tingkat Urgensi</label>
                        <select class="form-select" id="urgensiMasalah">
                            <option value="rendah">Rendah</option>
                            <option value="sedang">Sedang</option>
                            <option value="tinggi">Tinggi</option>
                        </select>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Laporkan',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const perangkat = document.getElementById('perangkatMasalah').value;
                const deskripsi = document.getElementById('deskripsiMasalah').value;
                const urgensi = document.getElementById('urgensiMasalah').value;

                if (!perangkat || !deskripsi) {
                    Swal.showValidationMessage('Harap isi semua field');
                }

                return { perangkat, deskripsi, urgensi };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Implementasi pelaporan masalah
                Swal.fire(
                    'Dilaporkan',
                    'Masalah berhasil dilaporkan. Tim teknis akan segera menindaklanjuti.',
                    'success'
                );
            }
        });
    });
});
</script>
@endpush
