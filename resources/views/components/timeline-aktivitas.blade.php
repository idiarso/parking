@props([
    'aktivitas' => [],
    'judul' => 'Timeline Aktivitas',
    'batasAktivitas' => 10
])

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-history me-2"></i>{{ $judul }}
        </h6>
    </div>
    <div class="card-body">
        <div class="timeline">
            @foreach($aktivitas->take($batasAktivitas) as $item)
                <div class="timeline-item">
                    <div class="timeline-icon 
                        @switch($item->tipe)
                            @case('masuk') bg-success @break
                            @case('keluar') bg-danger @break
                            @case('edit') bg-warning @break
                            @default bg-info @endswitch
                    ">
                        <i class="fas 
                            @switch($item->tipe)
                                @case('masuk') fa-sign-in-alt @break
                                @case('keluar') fa-sign-out-alt @break
                                @case('edit') fa-edit @break
                                @default fa-bell @endswitch
                        "></i>
                    </div>
                    <div class="timeline-content">
                        <h5 class="timeline-title">
                            {{ $item->judul }}
                        </h5>
                        <p class="timeline-description">
                            {{ $item->deskripsi }}
                        </p>
                        <div class="timeline-footer">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $item->waktu->diffForHumans() }}
                            </small>
                            @if($item->pengguna)
                                <span class="badge bg-secondary ms-2">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $item->pengguna->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @if($aktivitas->count() > $batasAktivitas)
    <div class="card-footer text-center">
        <button class="btn btn-outline-primary btn-sm" id="lihatSemuaAktivitas">
            <i class="fas fa-eye me-2"></i>Lihat Semua Aktivitas
        </button>
    </div>
    @endif
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50px;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 100px;
}

.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 15px;
    position: relative;
}

.timeline-title {
    margin-bottom: 10px;
    font-size: 1rem;
    color: #333;
}

.timeline-description {
    color: #6c757d;
    margin-bottom: 10px;
}

.timeline-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lihatSemuaButton = document.getElementById('lihatSemuaAktivitas');
    
    if (lihatSemuaButton) {
        lihatSemuaButton.addEventListener('click', function() {
            Swal.fire({
                title: 'Semua Aktivitas',
                html: `
                    <div class="text-start">
                        <p>Fitur melihat semua aktivitas akan segera hadir!</p>
                    </div>
                `,
                icon: 'info'
            });
        });
    }
});
</script>
@endpush
