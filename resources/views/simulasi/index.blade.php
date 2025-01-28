@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-calculator me-2"></i>Simulasi Parkir
            </h1>
        </div>
    </div>

    <x-simulasi-prediksi-parkir 
        :historiParkir="$historiParkir"
        :prediksiOkupansi="$prediksiOkupansi"
        :rekomendasiPengaturan="$rekomendasiPengaturan"
    />
</div>
@endsection
