@props([
    'logs' => [],
    'filterAktivitas' => ['semua', 'masuk', 'keluar', 'pembayaran', 'sistem']
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-history me-2"></i>Log Aktivitas
        </h6>
        <div class="btn-group" role="group">
            @foreach($filterAktivitas as $filter)
                <input type="radio" class="btn-check" name="filterLog" id="filter{{ ucfirst($filter) }}" 
                       autocomplete="off" 
                       {{ $filter === 'semua' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary btn-sm" for="filter{{ ucfirst($filter) }}">
                    {{ ucfirst($filter) }}
                </label>
            @endforeach
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="tabelLogAktivitas">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>Detail</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr data-tipe="{{ $log->tipe }}">
                        <td>{{ $log->waktu->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $log->pengguna->avatar }}" 
                                     class="rounded-circle me-2" 
                                     style="width: 30px; height: 30px;">
                                {{ $log->pengguna->name }}
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                @switch($log->tipe)
                                    @case('masuk') bg-success @break
                                    @case('keluar') bg-danger @break
                                    @case('pembayaran') bg-warning @break
                                    @case('sistem') bg-info @break
                                    @default bg-secondary @endswitch
                            ">
                                {{ ucfirst($log->tipe) }}
                            </span>
                        </td>
                        <td>{{ $log->deskripsi }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="detailLog({{ $log->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Total Log: {{ count($logs) }}
            </small>
            <button class="btn btn-outline-primary btn-sm" id="eksporLog">
                <i class="fas fa-file-export me-2"></i>Ekspor Log
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabel = $('#tabelLogAktivitas').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });

    const filterRadio = document.querySelectorAll('input[name="filterLog"]');
    filterRadio.forEach(radio => {
        radio.addEventListener('change', function() {
            const tipe = this.id.replace('filter', '').toLowerCase();
            
            if (tipe === 'semua') {
                tabel.column(2).search('').draw();
            } else {
                tabel.column(2).search(tipe).draw();
            }
        });
    });

    const eksporLogButton = document.getElementById('eksporLog');
    eksporLogButton.addEventListener('click', function() {
        Swal.fire({
            title: 'Ekspor Log Aktivitas',
            text: 'Pilih format ekspor log',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Excel',
            cancelButtonText: 'CSV'
        }).then((result) => {
            Swal.fire({
                title: 'Log Diekspor',
                text: `Log berhasil diekspor dalam format ${result.isConfirmed ? 'Excel' : 'CSV'}`,
                icon: 'success'
            });
        });
    });
});

function detailLog(id) {
    Swal.fire({
        title: 'Detail Log Aktivitas',
        html: `
            <div class="text-start">
                <p><strong>ID Log:</strong> ${id}</p>
                <p><strong>Waktu:</strong> 2024-01-28 10:30:45</p>
                <p><strong>Pengguna:</strong> Admin Sistem</p>
                <p><strong>Aktivitas:</strong> Masuk Kendaraan</p>
                <p><strong>Deskripsi:</strong> Kendaraan dengan plat B 1234 XYZ masuk ke slot A1</p>
            </div>
        `,
        icon: 'info'
    });
}
</script>
@endpush
