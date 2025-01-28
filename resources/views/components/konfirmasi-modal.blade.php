@props([
    'id' => 'konfirmasiModal',
    'judul' => 'Konfirmasi Tindakan',
    'pesan' => 'Apakah Anda yakin ingin melanjutkan?',
    'aksi' => '#',
    'metode' => 'POST',
    'jenisAksi' => 'primary'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $judul }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <i class="fas 
                        {{ $jenisAksi == 'danger' ? 'fa-exclamation-triangle text-danger' : 
                           ($jenisAksi == 'warning' ? 'fa-exclamation-circle text-warning' : 'fa-question-circle text-primary') }} 
                        fa-3x me-3">
                    </i>
                    <p class="mb-0">{{ $pesan }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <form action="{{ $aksi }}" method="{{ $metode }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-{{ $jenisAksi }}">
                        <i class="fas 
                            {{ $jenisAksi == 'danger' ? 'fa-trash' : 
                               ($jenisAksi == 'warning' ? 'fa-exclamation' : 'fa-check') }} 
                            me-2">
                        </i>
                        Konfirmasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
