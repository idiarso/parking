@extends('layouts.app')

@section('styles')
<style>
    .slot-parkir {
        width: 100px;
        height: 150px;
        margin: 10px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .slot-kosong {
        background-color: #e9ecef;
        border: 2px dashed #6c757d;
    }
    .slot-motor {
        background-color: #d4edda;
        border: 2px solid #28a745;
    }
    .slot-mobil {
        background-color: #cce5ff;
        border: 2px solid #007bff;
    }
    .slot-parkir:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Sistem Parkir</h6>
                    <div class="btn-group">
                        <a href="{{ route('parkir.masuk') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-sign-in-alt"></i> Pintu Masuk
                        </a>
                        <a href="{{ route('parkir.keluar') }}" class="btn btn-warning btn-sm ml-2">
                            <i class="fas fa-sign-out-alt"></i> Pintu Keluar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Slot Parkir Motor -->
                        <div class="col-md-6">
                            <h5 class="text-center mb-4">
                                <i class="fas fa-motorcycle"></i> Slot Parkir Motor
                            </h5>
                            <div class="d-flex flex-wrap justify-content-center">
                                @foreach($slotMotor as $slot)
                                <div class="slot-parkir {{ $slot->status == 'kosong' ? 'slot-kosong' : 'slot-motor' }}">
                                    <h6>{{ $slot->nomor_slot }}</h6>
                                    @if($slot->status != 'kosong')
                                        <small>{{ $slot->kendaraan->plat_nomor }}</small>
                                        <small>{{ $slot->kendaraan->waktu_masuk->diffForHumans() }}</small>
                                    @else
                                        <small>Tersedia</small>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Slot Parkir Mobil -->
                        <div class="col-md-6">
                            <h5 class="text-center mb-4">
                                <i class="fas fa-car"></i> Slot Parkir Mobil
                            </h5>
                            <div class="d-flex flex-wrap justify-content-center">
                                @foreach($slotMobil as $slot)
                                <div class="slot-parkir {{ $slot->status == 'kosong' ? 'slot-kosong' : 'slot-mobil' }}">
                                    <h6>{{ $slot->nomor_slot }}</h6>
                                    @if($slot->status != 'kosong')
                                        <small>{{ $slot->kendaraan->plat_nomor }}</small>
                                        <small>{{ $slot->kendaraan->waktu_masuk->diffForHumans() }}</small>
                                    @else
                                        <small>Tersedia</small>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Tarif -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tarif Parkir Motor</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>1 Jam Pertama</th>
                            <td>Rp. {{ number_format($tarifMotor->tarif_per_jam, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Jam Berikutnya</th>
                            <td>Rp. {{ number_format($tarifMotor->tarif_per_jam_berikutnya, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Maksimal Harian</th>
                            <td>Rp. {{ number_format($tarifMotor->tarif_maksimal, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tarif Parkir Mobil</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>1 Jam Pertama</th>
                            <td>Rp. {{ number_format($tarifMobil->tarif_per_jam, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Jam Berikutnya</th>
                            <td>Rp. {{ number_format($tarifMobil->tarif_per_jam_berikutnya, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Maksimal Harian</th>
                            <td>Rp. {{ number_format($tarifMobil->tarif_maksimal, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animasi slot parkir
    const slots = document.querySelectorAll('.slot-parkir');
    slots.forEach(slot => {
        slot.addEventListener('click', function() {
            if (this.classList.contains('slot-kosong')) {
                Swal.fire({
                    title: 'Slot Kosong',
                    text: 'Slot parkir ini tersedia untuk digunakan.',
                    icon: 'info'
                });
            } else {
                Swal.fire({
                    title: 'Detail Kendaraan',
                    html: this.innerHTML,
                    icon: 'information'
                });
            }
        });
    });
});
</script>
@endsection
