@extends('layouts.app')

@section('styles')
<style>
    .scanner-overlay {
        position: relative;
        border: 2px dashed #007bff;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }
    .scanner-overlay::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(0,123,255,0.1) 10px,
            rgba(0,123,255,0.1) 20px
        );
        z-index: -1;
        opacity: 0.5;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-sign-in-alt"></i> Pintu Masuk Kendaraan
            </h1>
        </div>
    </div>

    <div class="row">
        <!-- Scanner Kendaraan -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-camera"></i> Scanner Kendaraan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="scanner-overlay">
                        <div id="scanner-container" style="height: 400px;">
                            <!-- Placeholder untuk scanner -->
                            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                <i class="fas fa-qr-code fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Posisikan Kendaraan di Area Scanner</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Input Manual -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-keyboard"></i> Input Manual
                    </h6>
                </div>
                <div class="card-body">
                    <form id="inputManualForm">
                        <div class="mb-3">
                            <label class="form-label">Plat Nomor</label>
                            <input type="text" class="form-control" name="plat_nomor" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kendaraan</label>
                            <select class="form-select" name="jenis_kendaraan" required>
                                <option value="motor">Motor</option>
                                <option value="mobil">Mobil</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slot Parkir</label>
                            <select class="form-select" name="slot_parkir" required>
                                @foreach($slotKosong as $slot)
                                <option value="{{ $slot->id }}">{{ $slot->nomor_slot }} ({{ $slot->tipe }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kondisi Kendaraan</label>
                            <textarea class="form-control" name="kondisi_kendaraan" rows="3" placeholder="Catatan kondisi kendaraan"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Simpan Data Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Kendaraan Masuk Hari Ini -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Riwayat Kendaraan Masuk Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="riwayatMasukTable">
                            <thead>
                                <tr>
                                    <th>Plat Nomor</th>
                                    <th>Jenis</th>
                                    <th>Slot</th>
                                    <th>Waktu Masuk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatMasuk as $kendaraan)
                                <tr>
                                    <td>{{ $kendaraan->plat_nomor }}</td>
                                    <td>{{ ucfirst($kendaraan->jenis_kendaraan) }}</td>
                                    <td>{{ $kendaraan->slot->nomor_slot }}</td>
                                    <td>{{ $kendaraan->waktu_masuk->format('H:i:s') }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="detailKendaraan({{ $kendaraan->id }})">
                                            <i class="fas fa-eye"></i>
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
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi DataTable
    $('#riwayatMasukTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });

    // Inisialisasi Scanner
    function startScanner() {
        const scanner = new Html5Qrcode("scanner-container");
        const config = { fps: 10, qrbox: 250 };

        scanner.start({ facingMode: "environment" }, config, onScanSuccess);

        function onScanSuccess(decodedText, decodedResult) {
            Swal.fire({
                title: 'Kendaraan Terdeteksi',
                text: `Plat Nomor: ${decodedText}`,
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Proses',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proses data kendaraan
                    processScanResult(decodedText);
                }
            });

            scanner.pause(true);
        }
    }

    // Proses hasil scan
    function processScanResult(platNomor) {
        // Implementasi logika proses kendaraan masuk
        Swal.fire({
            title: 'Proses Kendaraan',
            text: `Memproses kendaraan dengan plat nomor ${platNomor}`,
            icon: 'info'
        });
    }

    // Input Manual Form
    $('#inputManualForm').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi Input',
            text: 'Apakah data kendaraan sudah benar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim data ke server
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Data kendaraan berhasil disimpan',
                    icon: 'success'
                });
            }
        });
    });

    // Memulai scanner
    startScanner();
});

function detailKendaraan(id) {
    Swal.fire({
        title: 'Detail Kendaraan',
        text: 'Fitur detail kendaraan akan segera hadir!',
        icon: 'info'
    });
}
</script>
@endsection
