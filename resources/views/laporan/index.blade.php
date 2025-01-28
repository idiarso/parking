@extends('layouts.app')

@section('styles')
<style>
    .laporan-card {
        transition: all 0.3s ease;
    }
    .laporan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-chart-bar me-2"></i>Laporan Sistem Parkir
            </h1>
        </div>
    </div>

    <!-- Komponen Laporan Analitik -->
    <x-laporan-analitik 
        :pendapatanBulanan="$pendapatanBulanan"
        :okupasiHarian="$okupasiHarian"
        :jenisKendaraan="$jenisKendaraan"
        :waktuPuncak="$waktuPuncak"
    />

    <!-- Akses Laporan Cepat -->
    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card laporan-card shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-file-pdf me-2"></i>Laporan Bulanan
                    </h5>
                    <a href="{{ route('laporan.bulanan') }}" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Unduh Laporan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card laporan-card shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-money-bill-wave me-2"></i>Laporan Pendapatan
                    </h5>
                    <a href="{{ route('laporan.pendapatan') }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Unduh Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
