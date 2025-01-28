@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-parking me-2"></i>Sistem Parkir
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt me-2"></i>Peta Slot Parkir
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($slotParkir as $slot)
                        <div class="col-md-3 mb-3">
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
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-money-bill-wave me-2"></i>Tarif Parkir
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Tarif/Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tarifParkir as $tarif)
                            <tr>
                                <td>
                                    <i class="fas 
                                        @if($tarif->jenis_kendaraan == 'motor') fa-motorcycle 
                                        @else fa-car 
                                        @endif me-2"></i>
                                    {{ ucfirst($tarif->jenis_kendaraan) }}
                                </td>
                                <td>Rp. {{ number_format($tarif->tarif_perjam, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
