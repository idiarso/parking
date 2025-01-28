@props([
    'statusParkir' => [],
    'batasPeringatan' => 80 // Persentase slot terisi untuk peringatan
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-satellite-dish me-2"></i>Monitoring Real-Time
        </h6>
        <div class="d-flex align-items-center">
            <span class="badge bg-success me-2" id="statusKoneksi">
                <i class="fas fa-circle me-1"></i>Online
            </span>
            <small class="text-muted" id="waktuTerakhirUpdate">
                Diperbarui: {{ now()->format('H:i:s') }}
            </small>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Status Slot Parkir</h6>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar 
                                @if($statusParkir['persentaseMotor'] >= $batasPeringatan) bg-danger 
                                @elseif($statusParkir['persentaseMotor'] >= 50) bg-warning 
                                @else bg-success @endif" 
                                role="progressbar" 
                                style="width: {{ $statusParkir['persentaseMotor'] }}%;" 
                                aria-valuenow="{{ $statusParkir['persentaseMotor'] }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                Motor: {{ $statusParkir['persentaseMotor'] }}%
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $statusParkir['terisiMotor'] }}/{{ $statusParkir['totalMotor'] }} Slot
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Status Slot Mobil</h6>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar 
                                @if($statusParkir['persentaseMobil'] >= $batasPeringatan) bg-danger 
                                @elseif($statusParkir['persentaseMobil'] >= 50) bg-warning 
                                @else bg-success @endif" 
                                role="progressbar" 
                                style="width: {{ $statusParkir['persentaseMobil'] }}%;" 
                                aria-valuenow="{{ $statusParkir['persentaseMobil'] }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                Mobil: {{ $statusParkir['persentaseMobil'] }}%
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $statusParkir['terisiMobil'] }}/{{ $statusParkir['totalMobil'] }} Slot
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Total Pendapatan Hari Ini</h6>
                        <h4 class="text-primary">
                            Rp. {{ number_format($statusParkir['pendapatanHariIni'], 0, ',', '.') }}
                        </h4>
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            {{ $statusParkir['persentasePendapatan'] }}% dari hari sebelumnya
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-12">
                <h6 class="mb-3">Kendaraan Terakhir</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Plat Nomor</th>
                                <th>Jenis</th>
                                <th>Slot</th>
                                <th>Waktu Masuk</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="kendaraanTerakhirBody">
                            @foreach($statusParkir['kendaraanTerakhir'] as $kendaraan)
                            <tr>
                                <td>{{ $kendaraan->plat_nomor }}</td>
                                <td>{{ ucfirst($kendaraan->jenis_kendaraan) }}</td>
                                <td>{{ $kendaraan->slot->nomor_slot }}</td>
                                <td>{{ $kendaraan->waktu_masuk->diffForHumans() }}</td>
                                <td>
                                    <span class="badge 
                                        @switch($kendaraan->status)
                                            @case('parkir') bg-warning @break
                                            @case('keluar') bg-success @break
                                            @default bg-secondary @endswitch
                                    ">
                                        {{ ucfirst($kendaraan->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="fas fa-server me-2"></i>Sistem Monitoring Aktif
            </small>
            <button class="btn btn-outline-primary btn-sm" id="detailMonitoring">
                <i class="fas fa-chart-line me-2"></i>Detail Monitoring
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusKoneksi = document.getElementById('statusKoneksi');
    const waktuTerakhirUpdate = document.getElementById('waktuTerakhirUpdate');
    const detailMonitoringBtn = document.getElementById('detailMonitoring');

    // Simulasi update real-time
    function updateMonitoring() {
        // Logika update data real-time
        waktuTerakhirUpdate.textContent = `Diperbarui: ${new Date().toLocaleTimeString()}`;
        
        // Simulasi perubahan status koneksi
        if (Math.random() > 0.9) {
            statusKoneksi.innerHTML = '<i class="fas fa-circle me-1"></i>Offline';
            statusKoneksi.classList.remove('bg-success');
            statusKoneksi.classList.add('bg-danger');
        } else {
            statusKoneksi.innerHTML = '<i class="fas fa-circle me-1"></i>Online';
            statusKoneksi.classList.remove('bg-danger');
            statusKoneksi.classList.add('bg-success');
        }
    }

    // Update setiap 5 detik
    setInterval(updateMonitoring, 5000);

    detailMonitoringBtn.addEventListener('click', function() {
        Swal.fire({
            title: 'Detail Monitoring',
            html: `
                <div class="text-start">
                    <p><strong>Total Slot:</strong> 50 (Motor: 30, Mobil: 20)</p>
                    <p><strong>Slot Terisi:</strong> Motor 24/30, Mobil 15/20</p>
                    <p><strong>Pendapatan Hari Ini:</strong> Rp. 5.500.000</p>
                    <p><strong>Kendaraan Masuk Hari Ini:</strong> 120</p>
                    <p><strong>Kendaraan Keluar Hari Ini:</strong> 98</p>
                </div>
            `,
            icon: 'info'
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.progress {
    border-radius: 10px;
    overflow: hidden;
}
.progress-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
@endpush
