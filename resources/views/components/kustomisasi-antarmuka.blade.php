@props([
    'tema' => [],
    'komponenAktif' => [],
    'aksesibilitas' => [],
    'preferensiPengguna' => []
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-palette me-2"></i>Kustomisasi Antarmuka Pengguna
        </h6>
        <div class="btn-group" role="group">
            <button class="btn btn-sm btn-outline-primary active" data-view="tema">
                <i class="fas fa-paint-brush"></i>
            </button>
            <button class="btn btn-sm btn-outline-primary" data-view="komponen">
                <i class="fas fa-cubes"></i>
            </button>
            <button class="btn btn-sm btn-outline-primary" data-view="aksesibilitas">
                <i class="fas fa-universal-access"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <!-- Tema -->
                <div id="temaSeksi" class="row">
                    @foreach($tema as $item)
                    <div class="col-md-4 mb-3">
                        <div class="card tema-item 
                            @if($item->aktif) border-primary shadow-lg 
                            @else border-secondary 
                            @endif" 
                            data-tema-id="{{ $item->id }}">
                            <div class="card-body text-center">
                                <div class="tema-preview mb-3" style="background-color: {{ $item->warna_utama }}; height: 100px;">
                                    <div class="tema-preview-detail" style="background-color: {{ $item->warna_aksen }}; height: 30px; margin-top: 70px;"></div>
                                </div>
                                <h5 class="card-title">{{ $item->nama }}</h5>
                                <p class="card-text text-muted">{{ $item->deskripsi }}</p>
                                <button class="btn 
                                    @if($item->aktif) btn-primary 
                                    @else btn-outline-secondary 
                                    @endif pilih-tema">
                                    @if($item->aktif) Aktif @else Pilih @endif
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Komponen -->
                <div id="komponenSeksi" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-puzzle-piece me-2"></i>Manajemen Komponen
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($komponenAktif as $komponen)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="{{ $komponen->icon }} me-2"></i>
                                                {{ $komponen->nama }}
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-komponen" 
                                                    type="checkbox" 
                                                    id="komponen-{{ $komponen->id }}"
                                                    @if($komponen->aktif) checked @endif
                                                    data-komponen-id="{{ $komponen->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aksesibilitas -->
                <div id="aksesibilitasSeksi" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-universal-access me-2"></i>Pengaturan Aksesibilitas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($aksesibilitas as $opsi)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="{{ $opsi->icon }} me-2"></i>
                                                {{ $opsi->nama }}
                                                <small class="d-block text-muted">{{ $opsi->deskripsi }}</small>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-aksesibilitas" 
                                                    type="checkbox" 
                                                    id="aksesibilitas-{{ $opsi->id }}"
                                                    @if($opsi->aktif) checked @endif
                                                    data-aksesibilitas-id="{{ $opsi->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-cog me-2"></i>Preferensi Pengguna
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="preferensiForm">
                            @foreach($preferensiPengguna as $preferensi)
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="{{ $preferensi->icon }} me-2"></i>
                                    {{ $preferensi->nama }}
                                </label>
                                @if($preferensi->tipe == 'select')
                                <select class="form-select" id="{{ $preferensi->id }}">
                                    @foreach($preferensi->pilihan as $pilihan)
                                    <option 
                                        value="{{ $pilihan->value }}"
                                        @if($pilihan->selected) selected @endif>
                                        {{ $pilihan->label }}
                                    </option>
                                    @endforeach
                                </select>
                                @elseif($preferensi->tipe == 'range')
                                <input type="range" 
                                    class="form-range" 
                                    id="{{ $preferensi->id }}"
                                    min="{{ $preferensi->min }}" 
                                    max="{{ $preferensi->max }}" 
                                    value="{{ $preferensi->value }}">
                                <div class="d-flex justify-content-between">
                                    <small>{{ $preferensi->min }}</small>
                                    <small>{{ $preferensi->max }}</small>
                                </div>
                                @endif
                                <small class="form-text text-muted">{{ $preferensi->deskripsi }}</small>
                            </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Simpan Preferensi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle View Mode
    const viewButtons = document.querySelectorAll('[data-view]');
    const seksiMap = {
        'tema': 'temaSeksi',
        'komponen': 'komponenSeksi',
        'aksesibilitas': 'aksesibilitasSeksi'
    };

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const viewMode = this.getAttribute('data-view');
            Object.keys(seksiMap).forEach(mode => {
                const seksi = document.getElementById(seksiMap[mode]);
                seksi.style.display = mode === viewMode ? 'block' : 'none';
            });
        });
    });

    // Pilih Tema
    const temaItems = document.querySelectorAll('.tema-item');
    temaItems.forEach(item => {
        const pilihTemaBtn = item.querySelector('.pilih-tema');
        pilihTemaBtn.addEventListener('click', function() {
            // Reset semua tema
            temaItems.forEach(t => {
                t.classList.remove('border-primary', 'shadow-lg');
                t.classList.add('border-secondary');
                
                const btn = t.querySelector('.pilih-tema');
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-secondary');
                btn.textContent = 'Pilih';
            });

            // Set tema terpilih
            item.classList.add('border-primary', 'shadow-lg');
            item.classList.remove('border-secondary');
            
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary');
            this.textContent = 'Aktif';

            const temaId = item.getAttribute('data-tema-id');
            // Kirim permintaan untuk mengaktifkan tema
            fetch('/tema/aktifkan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tema_id: temaId })
            })
            .then(response => response.json())
            .then(data => {
                // Terapkan tema
                document.body.className = data.kelas_tema;
                
                Swal.fire({
                    icon: 'success',
                    title: 'Tema Diperbarui',
                    text: 'Tema baru berhasil diaktifkan'
                });
            });
        });
    });

    // Toggle Komponen
    const toggleKomponen = document.querySelectorAll('.toggle-komponen');
    toggleKomponen.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const komponenId = this.getAttribute('data-komponen-id');
            const aktif = this.checked;

            fetch('/komponen/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    komponen_id: komponenId, 
                    aktif: aktif 
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'info',
                    title: 'Komponen Diperbarui',
                    text: `Komponen ${data.nama} ${aktif ? 'diaktifkan' : 'dinonaktifkan'}`
                });
            });
        });
    });

    // Toggle Aksesibilitas
    const toggleAksesibilitas = document.querySelectorAll('.toggle-aksesibilitas');
    toggleAksesibilitas.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const aksesibilitasId = this.getAttribute('data-aksesibilitas-id');
            const aktif = this.checked;

            fetch('/aksesibilitas/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    aksesibilitas_id: aksesibilitasId, 
                    aktif: aktif 
                })
            })
            .then(response => response.json())
            .then(data => {
                // Terapkan pengaturan aksesibilitas
                if (data.kelas_aksesibilitas) {
                    document.body.classList.toggle('aksesibilitas-aktif', aktif);
                }

                Swal.fire({
                    icon: 'info',
                    title: 'Aksesibilitas Diperbarui',
                    text: `Pengaturan ${data.nama} ${aktif ? 'diaktifkan' : 'dinonaktifkan'}`
                });
            });
        });
    });

    // Simpan Preferensi
    document.getElementById('preferensiForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const preferensi = {};
        @foreach($preferensiPengguna as $preferensi)
        preferensi['{{ $preferensi->id }}'] = document.getElementById('{{ $preferensi->id }}').value;
        @endforeach

        fetch('/preferensi/simpan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(preferensi)
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Preferensi Disimpan',
                text: 'Pengaturan pengguna berhasil diperbarui'
            });
        });
    });
});
</script>
@endpush
