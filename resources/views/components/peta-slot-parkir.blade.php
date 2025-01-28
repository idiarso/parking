@props([
    'slots' => [],
    'tipe' => 'all' // motor, mobil, all
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-parking me-2"></i>Peta Slot Parkir
        </h6>
        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="tipeSlot" id="slotSemua" autocomplete="off" checked>
            <label class="btn btn-outline-primary btn-sm" for="slotSemua">Semua</label>

            <input type="radio" class="btn-check" name="tipeSlot" id="slotMotor" autocomplete="off">
            <label class="btn btn-outline-primary btn-sm" for="slotMotor">Motor</label>

            <input type="radio" class="btn-check" name="tipeSlot" id="slotMobil" autocomplete="off">
            <label class="btn btn-outline-primary btn-sm" for="slotMobil">Mobil</label>
        </div>
    </div>
    <div class="card-body">
        <div class="row" id="petaSlotContainer">
            @foreach($slots as $slot)
                <div class="col-md-3 mb-3 slot-item" 
                     data-tipe="{{ $slot->tipe }}"
                     data-status="{{ $slot->status }}">
                    <div class="card slot-card 
                        @switch($slot->status)
                            @case('kosong') border-success @break
                            @case('terisi') border-danger @break
                            @case('maintenance') border-warning @break
                            @default border-secondary @endswitch
                        ">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">
                                    <i class="fas 
                                        @switch($slot->tipe)
                                            @case('motor') fa-motorcycle @break
                                            @case('mobil') fa-car @break
                                            @default fa-parking @endswitch
                                        me-2"></i>
                                    {{ $slot->nomor }}
                                </h5>
                                <p class="card-text text-muted">
                                    <span class="badge 
                                        @switch($slot->status)
                                            @case('kosong') bg-success @break
                                            @case('terisi') bg-danger @break
                                            @case('maintenance') bg-warning @break
                                            @default bg-secondary @endswitch
                                    ">
                                        {{ ucfirst($slot->status) }}
                                    </span>
                                </p>
                            </div>
                            @if($slot->status === 'terisi')
                                <button class="btn btn-info btn-sm" onclick="detailKendaraan({{ $slot->kendaraan->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="badge bg-success me-2">Kosong</span>
                        <span class="badge bg-danger me-2">Terisi</span>
                        <span class="badge bg-warning me-2">Maintenance</span>
                    </div>
                    <div>
                        <small class="text-muted">
                            Total Slot: {{ count($slots) }} 
                            | Motor: {{ $slots->where('tipe', 'motor')->count() }}
                            | Mobil: {{ $slots->where('tipe', 'mobil')->count() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slotContainer = document.getElementById('petaSlotContainer');
    const slotItems = document.querySelectorAll('.slot-item');
    const radioButtons = document.querySelectorAll('input[name="tipeSlot"]');

    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const tipe = this.id.replace('slot', '').toLowerCase();
            filterSlots(tipe);
        });
    });

    function filterSlots(tipe) {
        slotItems.forEach(item => {
            const itemTipe = item.getAttribute('data-tipe');
            
            if (tipe === 'semua' || itemTipe === tipe) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
});

function detailKendaraan(id) {
    Swal.fire({
        title: 'Detail Kendaraan',
        html: `
            <div class="text-start">
                <p><strong>Plat Nomor:</strong> B 1234 XYZ</p>
                <p><strong>Jenis:</strong> Motor</p>
                <p><strong>Waktu Masuk:</strong> 2024-01-28 10:30</p>
                <p><strong>Durasi:</strong> 3 Jam</p>
            </div>
        `,
        icon: 'info'
    });
}
</script>
@endpush

@push('styles')
<style>
.slot-card {
    transition: all 0.3s ease;
    border-width: 2px;
}
.slot-card:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
@endpush
