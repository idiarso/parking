@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-satellite-dish me-2"></i>Monitoring Parkir
            </h1>
        </div>
    </div>

    <x-monitoring-realtime-lanjutan 
        :slotParkir="$slotParkir"
        :statusTerkini="$statusTerkini"
        :riwayatTransaksi="$riwayatTransaksi"
    />
</div>
@endsection
