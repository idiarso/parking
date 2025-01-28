@extends('layouts.app')

@section('styles')
<style>
    .dashboard-card {
        transition: transform 0.3s ease;
    }
    .dashboard-card:hover {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Dashboard Parkir</h1>
        </div>
    </div>

    <div class="row">
        <!-- Statistik Kendaraan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Kendaraan Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $kendaraan_hari_ini }} Kendaraan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pendapatan Harian -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pendapatan Harian
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp. {{ number_format($pendapatan_hari_ini, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Slot Parkir -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Status Slot Parkir
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $slot_motor_terisi + $slot_mobil_terisi }} / {{ $slot_motor_total + $slot_mobil_total }} Terisi
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             style="width: {{ (($slot_motor_terisi + $slot_mobil_terisi) / ($slot_motor_total + $slot_mobil_total)) * 100 }}%" 
                                             aria-valuenow="{{ (($slot_motor_terisi + $slot_mobil_terisi) / ($slot_motor_total + $slot_mobil_total)) * 100 }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-parking fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slot Kosong -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Slot Kosong
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ ($slot_motor_total - $slot_motor_terisi) + ($slot_mobil_total - $slot_mobil_terisi) }} Slot
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Okupansi -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Okupansi Parkir per Jam</h6>
                </div>
                <div class="card-body">
                    <canvas id="okupasiChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Ringkasan Aktivitas -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Aktivitas</h6>
                </div>
                <div class="card-body">
                    <div class="text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Masuk
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Keluar
                        </span>
                    </div>
                    <hr>
                    <div class="mt-4 text-center small">
                        <div class="mb-2">Total Kendaraan Masuk: 260</div>
                        <div>Total Kendaraan Keluar: 245</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('okupasiChart').getContext('2d');
    
    // Default data jika $okupasiPerJam kosong
    @php
    $defaultOkupasiData = [
        ['jam' => 0, 'total_kendaraan' => 0],
        ['jam' => 6, 'total_kendaraan' => 5],
        ['jam' => 12, 'total_kendaraan' => 15],
        ['jam' => 18, 'total_kendaraan' => 10],
        ['jam' => 23, 'total_kendaraan' => 3]
    ];
    $okupasiData = $okupasiPerJam ?? $defaultOkupasiData;
    @endphp

    const okupasiData = @json($okupasiData);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: okupasiData.map(item => item.jam + ':00'),
            datasets: [{
                label: 'Jumlah Kendaraan',
                data: okupasiData.map(item => item.total_kendaraan),
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
                    title: {
                        display: true,
                        text: 'Jumlah Kendaraan'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Jam'
                    }
                }
            }
        }
    });
});
</script>
@endsection
