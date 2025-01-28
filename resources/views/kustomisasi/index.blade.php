@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-palette me-2"></i>Kustomisasi Antarmuka
            </h1>
        </div>
    </div>

    <x-kustomisasi-antarmuka 
        :tema="$tema"
        :komponenAktif="$komponenAktif"
        :aksesibilitas="$aksesibilitas"
        :preferensiPengguna="$preferensiPengguna"
    />
</div>
@endsection
