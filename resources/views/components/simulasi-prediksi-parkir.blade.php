@props([
    'historiParkir' => [],
    'prediksiOkupansi' => [],
    'rekomendasiPengaturan' => []
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-line me-2"></i>Simulasi dan Prediksi Parkir
        </h6>
        <div class="btn-group" role="group">
            <button class="btn btn-sm btn-outline-primary active" data-simulasi="okupansi">
                Okupansi
            </button>
            <button class="btn btn-sm btn-outline-primary" data-simulasi="pendapatan">
                Pendapatan
            </button>
            <button class="btn btn-sm btn-outline-primary" data-simulasi="kebutuhan">
                Kebutuhan Slot
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-area me-2"></i>Prediksi Okupansi
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="prediksiOkupansiChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history me-2"></i>Histori Parkir
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="historiParkirTable">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Total Kendaraan</th>
                                        <th>Okupansi (%)</th>
                                        <th>Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historiParkir as $data)
                                    <tr>
                                        <td>{{ $data->tanggal->format('d M Y') }}</td>
                                        <td>{{ $data->total_kendaraan }}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar 
                                                    @if($data->okupansi < 30) bg-success 
                                                    @elseif($data->okupansi < 70) bg-warning 
                                                    @else bg-danger 
                                                    @endif" 
                                                    role="progressbar" 
                                                    style="width: {{ $data->okupansi }}%"
                                                    aria-valuenow="{{ $data->okupansi }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                    {{ $data->okupansi }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($data->pendapatan, 0, ',', '.') }}</td>
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
                            <i class="fas fa-lightbulb me-2"></i>Rekomendasi Sistem
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($rekomendasiPengaturan as $rekomendasi)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="{{ $rekomendasi->icon }} me-2"></i>
                                    {{ $rekomendasi->judul }}
                                </span>
                                <span class="badge 
                                    @if($rekomendasi->prioritas == 'tinggi') bg-danger 
                                    @elseif($rekomendasi->prioritas == 'sedang') bg-warning 
                                    @else bg-info 
                                    @endif">
                                    {{ ucfirst($rekomendasi->prioritas) }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calculator me-2"></i>Simulasi Skenario
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="simulasiForm">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kendaraan</label>
                                <select class="form-select" id="jenisKendaraan">
                                    <option value="motor">Motor</option>
                                    <option value="mobil">Mobil</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Kendaraan</label>
                                <input type="number" class="form-control" id="jumlahKendaraan" min="1" max="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Durasi Parkir (Jam)</label>
                                <input type="number" class="form-control" id="durasiParkir" min="1" max="24">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-play me-2"></i>Jalankan Simulasi
                            </button>
                        </form>

                        <div id="hasilSimulasi" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Hasil Simulasi</h6>
                                    <p id="estimasiPendapatan"></p>
                                    <p id="estimasiOkupansi"></p>
                                    <p id="estimasiKetersediaanSlot"></p>
                                </div>
                            </div>
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
    const prediksiCtx = document.getElementById('prediksiOkupansiChart').getContext('2d');

    // Prediksi Okupansi Chart
    const prediksiChart = new Chart(prediksiCtx, {
        type: 'line',
        data: {
            labels: @json(array_keys($prediksiOkupansi)),
            datasets: [{
                label: 'Prediksi Okupansi (%)',
                data: @json(array_values($prediksiOkupansi)),
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
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

    // Tabel Histori
    $('#historiParkirTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ entri',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri'
        }
    });

    // Toggle Simulasi
    const simulasiButtons = document.querySelectorAll('[data-simulasi]');
    simulasiButtons.forEach(button => {
        button.addEventListener('click', function() {
            simulasiButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const simulasiMode = this.getAttribute('data-simulasi');
            // Implementasi logika filter/tampilan sesuai mode
            console.log('Mode Simulasi:', simulasiMode);
        });
    });

    // Simulasi Form
    $('#simulasiForm').on('submit', function(e) {
        e.preventDefault();
        
        const jenisKendaraan = $('#jenisKendaraan').val();
        const jumlahKendaraan = $('#jumlahKendaraan').val();
        const durasiParkir = $('#durasiParkir').val();

        // Simulasi sederhana
        const tarifMotor = 3000;
        const tarifMobil = 5000;
        const tarif = jenisKendaraan === 'motor' ? tarifMotor : tarifMobil;
        
        const estimasiPendapatan = jumlahKendaraan * tarif * durasiParkir;
        const estimasiOkupansi = (jumlahKendaraan / 50) * 100; // Asumsi 50 slot
        const estimasiKetersediaanSlot = 50 - jumlahKendaraan;

        $('#estimasiPendapatan').text(`Estimasi Pendapatan: Rp ${estimasiPendapatan.toLocaleString('id-ID')}`);
        $('#estimasiOkupansi').text(`Estimasi Okupansi: ${estimasiOkupansi.toFixed(2)}%`);
        $('#estimasiKetersediaanSlot').text(`Slot Tersedia: ${estimasiKetersediaanSlot}`);
        
        $('#hasilSimulasi').show();
    });
});
</script>
@endpush
