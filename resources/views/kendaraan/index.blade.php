@extends('layouts.app')

@section('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.1);
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Manajemen Kendaraan</h6>
                    <div class="btn-group">
                        <a href="{{ route('kendaraan.tambah') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Kendaraan
                        </a>
                        <button class="btn btn-success btn-sm ml-2" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-upload"></i> Import
                        </button>
                        <button class="btn btn-info btn-sm ml-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="fas fa-download"></i> Ekspor
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="kendaraanTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Plat Nomor</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Waktu Masuk</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kendaraan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->plat_nomor }}</td>
                                    <td>{{ $item->jenis_kendaraan }}</td>
                                    <td>{{ $item->waktu_masuk->format('d M Y H:i') }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $item->status == 'parkir' ? 'bg-warning' : 
                                               ($item->status == 'keluar' ? 'bg-success' : 'bg-secondary') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('kendaraan.detail', $item->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('kendaraan.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="konfirmasiHapus({{ $item->id }})" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $kendaraan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kendaraan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pilih File Excel</label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls">
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ekspor -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ekspor Data Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kendaraan.export') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Format Ekspor</label>
                        <select name="format" class="form-select">
                            <option value="xlsx">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Filter Tanggal</label>
                        <div class="input-group">
                            <input type="date" name="tanggal_mulai" class="form-control">
                            <span class="input-group-text">sampai</span>
                            <input type="date" name="tanggal_akhir" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Ekspor</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function konfirmasiHapus(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus kendaraan ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/kendaraan/hapus/${id}`)
                .then(response => {
                    Swal.fire(
                        'Terhapus!',
                        'Data kendaraan berhasil dihapus.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus data.',
                        'error'
                    );
                });
        }
    });
}

$(document).ready(function() {
    $('#kendaraanTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
});
</script>
@endsection
