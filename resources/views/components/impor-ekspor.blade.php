@props([
    'modelClass' => null,
    'templateUrl' => null,
    'tipeData' => 'kendaraan'
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-file-import me-2"></i>Impor & Ekspor {{ ucfirst($tipeData) }}
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Impor Data</h5>
                <form id="imporDataForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Pilih File Excel/CSV</label>
                        <input type="file" class="form-control" 
                               name="file_impor" 
                               accept=".csv,.xlsx,.xls"
                               required>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Pastikan format file sesuai dengan template
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-upload me-2"></i>Unggah & Impor
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <h5>Ekspor Data</h5>
                <div class="mb-3">
                    <label class="form-label">Format Ekspor</label>
                    <select class="form-select" id="formatEkspor">
                        <option value="xlsx">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                        <option value="pdf">PDF (.pdf)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Filter Data</label>
                    <select class="form-select" id="filterEkspor" multiple>
                        <option value="semua" selected>Semua Data</option>
                        <option value="bulan_ini">Bulan Ini</option>
                        <option value="minggu_ini">Minggu Ini</option>
                    </select>
                </div>
                <button id="tombolEkspor" class="btn btn-success w-100">
                    <i class="fas fa-download me-2"></i>Ekspor Data
                </button>
            </div>
        </div>

        @if($templateUrl)
        <div class="mt-3 text-center">
            <a href="{{ $templateUrl }}" class="btn btn-outline-secondary">
                <i class="fas fa-file-download me-2"></i>Unduh Template
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imporForm = document.getElementById('imporDataForm');
    const tombolEkspor = document.getElementById('tombolEkspor');
    const formatEkspor = document.getElementById('formatEkspor');
    const filterEkspor = document.getElementById('filterEkspor');

    imporForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(imporForm);

        Swal.fire({
            title: 'Impor Data',
            text: 'Sedang memproses file impor...',
            icon: 'info',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            Swal.fire({
                title: 'Impor Berhasil',
                text: 'Data berhasil diimpor ke sistem',
                icon: 'success'
            });
        });
    });

    tombolEkspor.addEventListener('click', function() {
        const format = formatEkspor.value;
        const filter = Array.from(filterEkspor.selectedOptions).map(opt => opt.value);

        Swal.fire({
            title: 'Ekspor Data',
            text: `Mengekspor data dalam format ${format}`,
            icon: 'info',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            Swal.fire({
                title: 'Ekspor Selesai',
                text: `File ${format.toUpperCase()} telah siap diunduh`,
                icon: 'success'
            });
        });
    });
});
</script>
@endpush
