@props([
    'konfigurasi' => [],
    'kategoriKonfigurasi' => [
        'umum' => 'Pengaturan Umum',
        'parkir' => 'Konfigurasi Parkir', 
        'pembayaran' => 'Metode Pembayaran',
        'keamanan' => 'Pengaturan Keamanan'
    ]
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-cogs me-2"></i>Konfigurasi Sistem
        </h6>
        <button class="btn btn-success btn-sm" id="simpanKonfigurasi">
            <i class="fas fa-save me-2"></i>Simpan Perubahan
        </button>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    @foreach($kategoriKonfigurasi as $kunci => $label)
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                id="tab-{{ $kunci }}" 
                                data-bs-toggle="pill" 
                                data-bs-target="#content-{{ $kunci }}" 
                                type="button" 
                                role="tab">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    @foreach($kategoriKonfigurasi as $kunci => $label)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                             id="content-{{ $kunci }}" 
                             role="tabpanel">
                            <h5 class="mb-3">{{ $label }}</h5>
                            
                            @switch($kunci)
                                @case('umum')
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Sistem</label>
                                            <input type="text" class="form-control" 
                                                   name="nama_sistem" 
                                                   value="{{ $konfigurasi['nama_sistem'] ?? 'Sistem Parkir' }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Zona Waktu</label>
                                            <select class="form-select" name="zona_waktu">
                                                <option value="Asia/Jakarta" selected>WIB (Indonesia Barat)</option>
                                                <option value="Asia/Makassar">WITA (Indonesia Tengah)</option>
                                                <option value="Asia/Jayapura">WIT (Indonesia Timur)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Deskripsi Sistem</label>
                                            <textarea class="form-control" rows="3" name="deskripsi_sistem">
                                                {{ $konfigurasi['deskripsi_sistem'] ?? 'Sistem manajemen parkir modern dan efisien' }}
                                            </textarea>
                                        </div>
                                    </div>
                                    @break

                                @case('parkir')
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kapasitas Motor</label>
                                            <input type="number" class="form-control" 
                                                   name="kapasitas_motor" 
                                                   value="{{ $konfigurasi['kapasitas_motor'] ?? 30 }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kapasitas Mobil</label>
                                            <input type="number" class="form-control" 
                                                   name="kapasitas_mobil" 
                                                   value="{{ $konfigurasi['kapasitas_mobil'] ?? 20 }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Batas Waktu Parkir Motor (Jam)</label>
                                            <input type="number" class="form-control" 
                                                   name="batas_parkir_motor" 
                                                   value="{{ $konfigurasi['batas_parkir_motor'] ?? 12 }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Batas Waktu Parkir Mobil (Jam)</label>
                                            <input type="number" class="form-control" 
                                                   name="batas_parkir_mobil" 
                                                   value="{{ $konfigurasi['batas_parkir_mobil'] ?? 24 }}">
                                        </div>
                                    </div>
                                    @break

                                @case('pembayaran')
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Metode Pembayaran Aktif</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="metode_pembayaran[]" 
                                                       value="tunai" 
                                                       {{ in_array('tunai', $konfigurasi['metode_pembayaran'] ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label">Tunai</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="metode_pembayaran[]" 
                                                       value="qris" 
                                                       {{ in_array('qris', $konfigurasi['metode_pembayaran'] ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label">QRIS</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="metode_pembayaran[]" 
                                                       value="transfer" 
                                                       {{ in_array('transfer', $konfigurasi['metode_pembayaran'] ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label">Transfer Bank</label>
                                            </div>
                                        </div>
                                    </div>
                                    @break

                                @case('keamanan')
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Durasi Sesi Login</label>
                                            <input type="number" class="form-control" 
                                                   name="durasi_sesi" 
                                                   value="{{ $konfigurasi['durasi_sesi'] ?? 60 }}">
                                            <small class="form-text text-muted">Dalam menit</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Percobaan Login Maksimal</label>
                                            <input type="number" class="form-control" 
                                                   name="percobaan_login" 
                                                   value="{{ $konfigurasi['percobaan_login'] ?? 3 }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="autentikasi_dua_faktor" 
                                                       {{ ($konfigurasi['autentikasi_dua_faktor'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label">Aktifkan Autentikasi Dua Faktor</label>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const simpanKonfigurasiBtn = document.getElementById('simpanKonfigurasi');

    simpanKonfigurasiBtn.addEventListener('click', function() {
        const konfigurasi = {};
        const kategori = ['umum', 'parkir', 'pembayaran', 'keamanan'];

        kategori.forEach(kat => {
            const kategoriForm = document.getElementById(`content-${kat}`);
            const inputs = kategoriForm.querySelectorAll('input, textarea, select');

            inputs.forEach(input => {
                let value;
                if (input.type === 'checkbox') {
                    if (input.name.includes('[]')) {
                        // Checkbox group
                        if (!konfigurasi[input.name.replace('[]', '')])
                            konfigurasi[input.name.replace('[]', '')] = [];
                        
                        if (input.checked) {
                            konfigurasi[input.name.replace('[]', '')].push(input.value);
                        }
                    } else {
                        // Single checkbox
                        value = input.checked;
                        konfigurasi[input.name] = value;
                    }
                } else {
                    value = input.value;
                    konfigurasi[input.name] = value;
                }
            });
        });

        Swal.fire({
            title: 'Simpan Konfigurasi',
            text: 'Apakah Anda yakin ingin menyimpan perubahan konfigurasi?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Konfigurasi Tersimpan:', konfigurasi);
                Swal.fire({
                    title: 'Konfigurasi Disimpan',
                    text: 'Pengaturan sistem berhasil diperbarui',
                    icon: 'success'
                });
            }
        });
    });
});
</script>
@endpush
