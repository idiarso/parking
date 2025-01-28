@props([
    'slotParkir' => [],
    'statusTerkini' => [
        'total_slot' => 0,
        'slot_terisi' => 0,
        'slot_kosong' => 0,
        'pendapatan_hari_ini' => 0,
        'kendaraan_masuk' => 0
    ],
    'riwayatTransaksi' => []
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-satellite-dish me-2"></i>Monitoring Real-Time Parkir
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
                <div class="row" id="slotParkirContainer">
                    @foreach($slotParkir as $slot)
                    <div class="col-md-3 mb-3 slot-item">
                        <div class="card 
                            @if($slot->status == 'kosong') border-success 
                            @elseif($slot->status == 'terisi') border-danger 
                            @else border-warning 
                            @endif">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $slot->nomor_slot }}</h5>
                                <p class="card-text">
                                    <span class="badge 
                                        @if($slot->status == 'kosong') bg-success 
                                        @elseif($slot->status == 'terisi') bg-danger 
                                        @else bg-warning 
                                        @endif">
                                        {{ ucfirst($slot->status) }}
                                    </span>
                                </p>
                                @if($slot->kendaraan)
                                <div class="mt-2">
                                    <small>
                                        <i class="fas 
                                            @if($slot->kendaraan->jenis == 'motor') fa-motorcycle 
                                            @else fa-car 
                                            @endif me-2"></i>
                                        {{ $slot->kendaraan->plat_nomor }}
                                    </small>
                                    <div class="text-muted mt-1">
                                        <small>
                                            Masuk: {{ $slot->kendaraan->waktu_masuk->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie me-2"></i>Status Parkir
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="h4 font-weight-bold text-success">
                                    {{ $statusTerkini['slot_kosong'] }}
                                </div>
                                <small class="text-muted">Slot Kosong</small>
                            </div>
                            <div class="col-6 text-center">
                                <div class="h4 font-weight-bold text-danger">
                                    {{ $statusTerkini['slot_terisi'] }}
                                </div>
                                <small class="text-muted">Slot Terisi</small>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="h4 font-weight-bold text-primary">
                                    {{ $statusTerkini['kendaraan_masuk'] }}
                                </div>
                                <small class="text-muted">Kendaraan Masuk</small>
                            </div>
                            <div class="col-6 text-center">
                                <div class="h4 font-weight-bold text-info">
                                    Rp {{ number_format($statusTerkini['pendapatan_hari_ini'], 0, ',', '.') }}
                                </div>
                                <small class="text-muted">Pendapatan Hari Ini</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history me-2"></i>Riwayat Transaksi
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($riwayatTransaksi as $transaksi)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge 
                                        @if($transaksi->jenis_kendaraan == 'motor') bg-primary 
                                        @else bg-warning 
                                        @endif me-2">
                                        {{ strtoupper($transaksi->jenis_kendaraan[0]) }}
                                    </span>
                                    {{ $transaksi->plat_nomor }}
                                </div>
                                <small class="text-muted">
                                    {{ $transaksi->waktu_masuk->diffForHumans() }}
                                </small>
                            </div>
                            @endforeach
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
    // Toggle View Mode
    const viewButtons = document.querySelectorAll('[data-view]');
    const slotContainer = document.getElementById('slotParkirContainer');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const viewMode = this.getAttribute('data-view');
            if (viewMode === 'list') {
                slotContainer.classList.remove('row');
                slotContainer.classList.add('list-group');
                
                document.querySelectorAll('.slot-item').forEach(item => {
                    item.classList.remove('col-md-3', 'mb-3');
                    item.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                });
            } else {
                slotContainer.classList.add('row');
                slotContainer.classList.remove('list-group');
                
                document.querySelectorAll('.slot-item').forEach(item => {
                    item.classList.add('col-md-3', 'mb-3');
                    item.classList.remove('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                });
            }
        });
    });

    // WebSocket untuk Update Real-Time
    const socket = new WebSocket('ws://localhost:6001/parking-monitor');
    
    socket.onmessage = function(event) {
        const data = JSON.parse(event.data);
        
        // Update slot status
        updateSlotStatus(data.slotUpdates);
        
        // Update statistik
        updateStatistics(data.statistics);
        
        // Update riwayat transaksi
        updateTransactionHistory(data.recentTransactions);
    };

    function updateSlotStatus(slotUpdates) {
        slotUpdates.forEach(slot => {
            const slotElement = document.querySelector(`.card[data-slot-id="${slot.id}"]`);
            if (slotElement) {
                slotElement.classList.remove('border-success', 'border-danger', 'border-warning');
                slotElement.classList.add(`border-${slot.status === 'kosong' ? 'success' : 'danger'}`);
                
                const badgeElement = slotElement.querySelector('.badge');
                badgeElement.classList.remove('bg-success', 'bg-danger', 'bg-warning');
                badgeElement.classList.add(`bg-${slot.status === 'kosong' ? 'success' : 'danger'}`);
                badgeElement.textContent = slot.status.charAt(0).toUpperCase() + slot.status.slice(1);
            }
        });
    }

    function updateStatistics(stats) {
        document.querySelector('.text-success').textContent = stats.slot_kosong;
        document.querySelector('.text-danger').textContent = stats.slot_terisi;
        document.querySelector('.text-primary').textContent = stats.kendaraan_masuk;
        document.querySelector('.text-info').textContent = 'Rp ' + stats.pendapatan_hari_ini.toLocaleString('id-ID');
    }

    function updateTransactionHistory(transactions) {
        const historyContainer = document.querySelector('.list-group');
        historyContainer.innerHTML = transactions.map(transaksi => `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge ${transaksi.jenis_kendaraan === 'motor' ? 'bg-primary' : 'bg-warning'} me-2">
                        ${transaksi.jenis_kendaraan[0].toUpperCase()}
                    </span>
                    ${transaksi.plat_nomor}
                </div>
                <small class="text-muted">${transaksi.waktu_masuk}</small>
            </div>
        `).join('');
    }
});
</script>
@endpush
