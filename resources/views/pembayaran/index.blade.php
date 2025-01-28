@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-money-check-alt me-2"></i>Manajemen Pembayaran
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($statistikPembayaran['total_pendapatan'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Transaksi Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistikPembayaran['transaksi_hari_ini'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Rata-rata Pembayaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($statistikPembayaran['rata_pendapatan'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history me-2"></i>Transaksi Terakhir
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="tabelTransaksi">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Kendaraan</th>
                            <th>Total Bayar</th>
                            <th>Metode</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaranTerakhir as $pembayaran)
                        <tr>
                            <td>{{ $pembayaran->kode_transaksi }}</td>
                            <td>
                                <span class="badge 
                                    @if($pembayaran->kendaraan->jenis == 'motor') bg-primary 
                                    @else bg-warning 
                                    @endif">
                                    {{ strtoupper($pembayaran->kendaraan->jenis[0]) }}
                                </span>
                                {{ $pembayaran->kendaraan->plat_nomor }}
                            </td>
                            <td>Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge 
                                    @if($pembayaran->metode_pembayaran == 'tunai') bg-success 
                                    @elseif($pembayaran->metode_pembayaran == 'qris') bg-info 
                                    @else bg-secondary 
                                    @endif">
                                    {{ ucfirst($pembayaran->metode_pembayaran) }}
                                </span>
                            </td>
                            <td>{{ $pembayaran->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('pembayaran.cetak-struk', $pembayaran->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#tabelTransaksi').DataTable({
        responsive: true,
        order: [[4, 'desc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ entri',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri'
        }
    });
});
</script>
@endpush
