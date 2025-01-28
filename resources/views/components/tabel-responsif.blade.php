@props([
    'id' => 'tabelResponsif',
    'judul' => 'Tabel Data',
    'kolom' => [],
    'data' => [],
    'aksi' => false,
    'tambahAksi' => false
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">{{ $judul }}</h6>
        @if($tambahAksi)
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus me-2"></i>Tambah
            </button>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="{{ $id }}">
                <thead>
                    <tr>
                        @foreach($kolom as $namaKolom)
                            <th>{{ $namaKolom }}</th>
                        @endforeach
                        @if($aksi)
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            @foreach($kolom as $key)
                                <td>{{ $item->$key ?? '-' }}</td>
                            @endforeach
                            @if($aksi)
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-info btn-sm" onclick="detailItem({{ $item->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editItem({{ $item->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="hapusItem({{ $item->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#{{ $id }}').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
});

function detailItem(id) {
    Swal.fire({
        title: 'Detail Item',
        text: `Menampilkan detail item dengan ID ${id}`,
        icon: 'info'
    });
}

function editItem(id) {
    Swal.fire({
        title: 'Edit Item',
        text: `Mempersiapkan edit item dengan ID ${id}`,
        icon: 'info'
    });
}

function hapusItem(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus item dengan ID ${id}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    });
}
</script>
@endpush
