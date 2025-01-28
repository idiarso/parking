@extends('layouts.app')

@section('styles')
<style>
    .kendaraan-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .kendaraan-card:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .pembayaran-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-sign-out-alt"></i> Pintu Keluar Kendaraan
            </h1>
        </div>
    </div>

    <div class="row">
        <!-- Daftar Kendaraan Parkir -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Kendaraan Parkir
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($kendaraanDiparkir as $kendaraan)
                    <div class="card kendaraan-card mb-3" onclick="pilihKendaraan({{ $kendaraan->id }})">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1">{{ $kendaraan->plat_nomor }}</h6>
                                    <p class="card-text text-muted">
                                        {{ ucfirst($kendaraan->jenis_kendaraan) }} | 
                                        Slot {{ $kendaraan->slot->nomor_slot }}
                                    </p>
                                </div>
                                <div>
                                    <span class="badge 
                                        {{ $kendaraan->durasi_parkir <= 1 ? 'bg-success' : 
                                           ($kendaraan->durasi_parkir <= 3 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $kendaraan->durasi_parkir }} Jam
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Detail Pembayaran -->
        <div class="col-md-8">
            <div class="card shadow mb-4" id="pembayaranContainer" style="display:none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-receipt"></i> Detail Pembayaran
                    </h6>
                </div>
                <div class="card-body pembayaran-container">
                    <form id="pembayaranForm">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informasi Kendaraan</h5>
                                <div class="mb-3">
                                    <label class="form-label">Plat Nomor</label>
                                    <input type="text" class="form-control" id="platNomor" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kendaraan</label>
                                    <input type="text" class="form-control" id="jenisKendaraan" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slot Parkir</label>
                                    <input type="text" class="form-control" id="slotParkir" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Rincian Biaya</h5>
                                <div class="mb-3">
                                    <label class="form-label">Waktu Masuk</label>
                                    <input type="text" class="form-control" id="waktuMasuk" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Waktu Keluar</label>
                                    <input type="text" class="form-control" id="waktuKeluar" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Durasi Parkir</label>
                                    <input type="text" class="form-control" id="durasiParkir" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Total Biaya</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" id="totalBiaya" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-money-bill-wave"></i> Proses Pembayaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Kendaraan Keluar -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Riwayat Kendaraan Keluar Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="riwayatKeluarTable">
                            <thead>
                                <tr>
                                    <th>Plat Nomor</th>
                                    <th>Jenis</th>
                                    <th>Durasi</th>
                                    <th>Biaya</th>
                                    <th>Waktu Keluar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatKeluar as $kendaraan)
                                <tr>
                                    <td>{{ $kendaraan->plat_nomor }}</td>
                                    <td>{{ ucfirst($kendaraan->jenis_kendaraan) }}</td>
                                    <td>{{ $kendaraan->durasi_parkir }} Jam</td>
                                    <td>Rp. {{ number_format($kendaraan->biaya_parkir, 0, ',', '.') }}</td>
                                    <td>{{ $kendaraan->waktu_keluar->format('H:i:s') }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="cetakStruk({{ $kendaraan->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    // Inisialisasi DataTable
    $('#riwayatKeluarTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });

    // Pembayaran Form
    $('#pembayaranForm').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: 'Apakah Anda yakin ingin menyelesaikan pembayaran?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim data pembayaran ke server
                Swal.fire({
                    title: 'Pembayaran Berhasil',
                    text: 'Terima kasih!',
                    icon: 'success'
                });
            }
        });
    });
});

function pilihKendaraan(id) {
    // Simulasi pengambilan data kendaraan
    const kendaraan = {
        platNomor: 'B 1234 XYZ',
        jenisKendaraan: 'Motor',
        slotParkir: 'A1',
        waktuMasuk: '2024-01-28 08:30:00',
        waktuKeluar: '2024-01-28 12:45:00',
        durasiParkir: '4 Jam 15 Menit',
        totalBiaya: '20000'
    };

    // Isi form pembayaran
    $('#platNomor').val(kendaraan.platNomor);
    $('#jenisKendaraan').val(kendaraan.jenisKendaraan);
    $('#slotParkir').val(kendaraan.slotParkir);
    $('#waktuMasuk').val(kendaraan.waktuMasuk);
    $('#waktuKeluar').val(kendaraan.waktuKeluar);
    $('#durasiParkir').val(kendaraan.durasiParkir);
    $('#totalBiaya').val(kendaraan.totalBiaya);

    // Tampilkan container pembayaran
    $('#pembayaranContainer').show();
}

function cetakStruk(id) {
    Swal.fire({
        title: 'Cetak Struk',
        text: 'Fitur cetak struk akan segera hadir!',
        icon: 'info'
    });
}
</script>
@endsection
