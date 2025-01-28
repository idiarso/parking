@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-server me-2"></i>Manajemen Infrastruktur
            </h1>
        </div>
    </div>

    <x-manajemen-perangkat 
        :daftarPerangkat="$daftarPerangkat"
        :statusInfrastruktur="$statusInfrastruktur"
        :logPemeliharaan="$logPemeliharaan"
    />
</div>
@endsection
