@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-server me-2"></i>Manajemen Perangkat
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Perangkat
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistikPerangkat['total'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Perangkat Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistikPerangkat['aktif'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Dalam Pemeliharaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistikPerangkat['pemeliharaan'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Perangkat Rusak
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $statistikPerangkat['rusak'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Daftar Perangkat
                    </h6>
                    <button class="btn btn-primary btn-sm" id="tambahPerangkat">
                        <i class="fas fa-plus me-1"></i>Tambah Perangkat
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tabelPerangkat">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($perangkat as $device)
                                <tr>
                                    <td>{{ $device->nama }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ \App\Models\Perangkat::JENIS[$device->jenis] }}
                                        </span>
                                    </td>
                                    <td>{{ $device->lokasi }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($device->status == 'aktif') bg-success
                                            @elseif($device->status == 'pemeliharaan') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ \App\Models\Perangkat::STATUS[$device->status] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info kesehatan-perangkat" 
                                                    data-id="{{ $device->id }}">
                                                <i class="fas fa-heartbeat"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning pemeliharaan-perangkat" 
                                                    data-id="{{ $device->id }}">
                                                <i class="fas fa-tools"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Log Pemeliharaan Terakhir
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled timeline-log">
                        @foreach($logPemeliharaan['log_terakhir'] as $log)
                        <li class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $log->perangkat->nama }}</strong>
                                <small class="text-muted">
                                    {{ $log->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <p class="text-sm">
                                {{ $log->deskripsi }}
                                <span class="badge 
                                    @if($log->status == 'selesai') bg-success
                                    @elseif($log->status == 'proses') bg-warning
                                    @else bg-danger
                                    @endif">
                                    {{ $log->status }}
                                </span>
                            </p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Perangkat -->
<div class="modal fade" id="modalTambahPerangkat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Perangkat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahPerangkat">
                    <div class="mb-3">
                        <label class="form-label">Nama Perangkat</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Perangkat</label>
                        <select class="form-select" name="jenis" required>
                            <option value="">Pilih Jenis</option>
                            @foreach(\App\Models\Perangkat::JENIS as $kode => $nama)
                            <option value="{{ $kode }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat IP (Opsional)</label>
                        <input type="text" class="form-control" name="ip_address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Seri (Opsional)</label>
                        <input type="text" class="form-control" name="serial_number">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanPerangkat">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#tabelPerangkat').DataTable({
        responsive: true,
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ entri'
        }
    });

    // Tambah Perangkat
    $('#tambahPerangkat').on('click', function() {
        $('#modalTambahPerangkat').modal('show');
    });

    $('#simpanPerangkat').on('click', function() {
        let form = $('#formTambahPerangkat');
        let formData = form.serialize();

        $.ajax({
            url: "{{ route('manajemen-perangkat.tambah') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = Object.values(errors).flat().join('\n');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage
                });
            }
        });
    });

    // Kesehatan Perangkat
    $('.kesehatan-perangkat').on('click', function() {
        let perangkatId = $(this).data('id');

        $.ajax({
            url: `/manajemen-perangkat/${perangkatId}/kesehatan`,
            method: 'GET',
            success: function(response) {
                let kondisi = response.kondisi;
                let statusKoneksi = kondisi.status_koneksi;
                let pemeliharaan = kondisi.terakhir_dipelihara;

                Swal.fire({
                    title: 'Kesehatan Perangkat',
                    html: `
                        <div class="text-start">
                            <p><strong>Status Koneksi:</strong> 
                                <span class="badge ${statusKoneksi === 'online' ? 'bg-success' : 'bg-danger'}">
                                    ${statusKoneksi.toUpperCase()}
                                </span>
                            </p>
                            <p><strong>Terakhir Dipelihara:</strong> 
                                ${pemeliharaan.terakhir ? pemeliharaan.terakhir : 'Belum pernah'}
                            </p>
                            <p><strong>Butuh Pemeliharaan:</strong> 
                                <span class="badge ${pemeliharaan.perlu_pemeliharaan ? 'bg-warning' : 'bg-success'}">
                                    ${pemeliharaan.perlu_pemeliharaan ? 'Ya' : 'Tidak'}
                                </span>
                            </p>
                        </div>
                    `,
                    icon: 'info'
                });
            }
        });
    });

    // Pemeliharaan Perangkat
    $('.pemeliharaan-perangkat').on('click', function() {
        let perangkatId = $(this).data('id');

        Swal.fire({
            title: 'Catat Pemeliharaan',
            html: `
                <textarea id="deskripsi" class="form-control" placeholder="Deskripsi pemeliharaan"></textarea>
                <select id="status" class="form-select mt-2">
                    <option value="selesai">Selesai</option>
                    <option value="proses">Sedang Dikerjakan</option>
                    <option value="ditunda">Ditunda</option>
                </select>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            preConfirm: () => {
                const deskripsi = Swal.getPopup().querySelector('#deskripsi').value;
                const status = Swal.getPopup().querySelector('#status').value;

                return { deskripsi, status };
            }
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: `/manajemen-perangkat/${perangkatId}/pemeliharaan`,
                    method: 'POST',
                    data: {
                        deskripsi: result.value.deskripsi,
                        status: result.value.status
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
