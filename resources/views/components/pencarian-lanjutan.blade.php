@props([
    'modelClass' => null,
    'fields' => [],
    'filterTambahan' => []
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-search me-2"></i>Pencarian Lanjutan
        </h6>
        <button class="btn btn-primary btn-sm" id="resetPencarian">
            <i class="fas fa-sync me-2"></i>Reset
        </button>
    </div>
    <div class="card-body">
        <form id="pencarianLanjutanForm">
            <div class="row">
                @foreach($fields as $field)
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ $field['label'] }}</label>
                        @switch($field['type'])
                            @case('text')
                                <input type="text" class="form-control" name="{{ $field['name'] }}">
                                @break
                            @case('select')
                                <select class="form-select" name="{{ $field['name'] }}">
                                    <option value="">Semua</option>
                                    @foreach($field['options'] as $option)
                                        <option value="{{ $option['value'] }}">
                                            {{ $option['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @break
                            @case('date')
                                <input type="date" class="form-control" name="{{ $field['name'] }}">
                                @break
                            @case('number')
                                <input type="number" class="form-control" name="{{ $field['name'] }}">
                                @break
                        @endswitch
                    </div>
                @endforeach

                @foreach($filterTambahan as $filter)
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ $filter['label'] }}</label>
                        {!! $filter['html'] !!}
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-filter me-2"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pencarianLanjutanForm');
    const resetButton = document.getElementById('resetPencarian');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData).toString();

        Swal.fire({
            title: 'Mencari Data...',
            text: 'Sedang memproses pencarian dengan filter yang dipilih',
            icon: 'info',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            // Implementasi pencarian dengan AJAX
            console.log('Parameter Pencarian:', searchParams);
        });
    });

    resetButton.addEventListener('click', function() {
        form.reset();
        Swal.fire({
            title: 'Reset Pencarian',
            text: 'Filter pencarian telah dikembalikan ke pengaturan awal',
            icon: 'success',
            timer: 1500
        });
    });
});
</script>
@endpush
