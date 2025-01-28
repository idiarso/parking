@props([
    'kendaraan' => null,
    'metodePembayaran' => ['tunai', 'qris', 'transfer']
])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-money-bill-wave me-2"></i>Pembayaran Parkir
        </h6>
        <span class="badge bg-info">
            <i class="fas fa-clock me-2"></i>{{ now()->format('H:i:s') }}
        </span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Informasi Kendaraan</h5>
                <div class="mb-3">
                    <label class="form-label">Plat Nomor</label>
                    <input type="text" class="form-control" value="{{ $kendaraan->plat_nomor }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kendaraan</label>
                    <input type="text" class="form-control" value="{{ ucfirst($kendaraan->jenis_kendaraan) }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Slot Parkir</label>
                    <input type="text" class="form-control" value="{{ $kendaraan->slot->nomor_slot }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <h5>Rincian Biaya</h5>
                <div class="mb-3">
                    <label class="form-label">Waktu Masuk</label>
                    <input type="text" class="form-control" value="{{ $kendaraan->waktu_masuk->format('Y-m-d H:i:s') }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Waktu Keluar</label>
                    <input type="text" class="form-control" value="{{ now()->format('Y-m-d H:i:s') }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Durasi Parkir</label>
                    <input type="text" class="form-control" value="{{ $kendaraan->durasi_parkir }} Jam" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Total Biaya</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control" value="{{ number_format($kendaraan->biaya_parkir, 0, ',', '.') }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-12">
                <h5>Metode Pembayaran</h5>
                <div class="btn-group w-100" role="group">
                    @foreach($metodePembayaran as $metode)
                    <input type="radio" class="btn-check" name="metodePembayaran" id="metode{{ ucfirst($metode) }}" autocomplete="off">
                    <label class="btn btn-outline-primary" for="metode{{ ucfirst($metode) }}">
                        <i class="fas 
                            @switch($metode)
                                @case('tunai') fa-money-bill-alt @break
                                @case('qris') fa-qrcode @break
                                @case('transfer') fa-university @break
                            @endswitch
                        me-2"></i>
                        {{ ucfirst($metode) }}
                    </label>
                    @endforeach
                </div>

                <div id="detailPembayaran" class="mt-3" style="display:none;">
                    <div class="card">
                        <div class="card-body">
                            <h6 id="judulMetodePembayaran"></h6>
                            <div id="kontenMetodePembayaran"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <button id="tombolBayar" class="btn btn-success btn-lg w-100" disabled>
                    <i class="fas fa-check-circle me-2"></i>Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const metodePembayaran = document.querySelectorAll('input[name="metodePembayaran"]');
    const detailPembayaran = document.getElementById('detailPembayaran');
    const judulMetodePembayaran = document.getElementById('judulMetodePembayaran');
    const kontenMetodePembayaran = document.getElementById('kontenMetodePembayaran');
    const tombolBayar = document.getElementById('tombolBayar');

    metodePembayaran.forEach(metode => {
        metode.addEventListener('change', function() {
            const metodeTerpilih = this.id.replace('metode', '').toLowerCase();
            
            tombolBayar.disabled = false;
            detailPembayaran.style.display = 'block';

            switch(metodeTerpilih) {
                case 'tunai':
                    judulMetodePembayaran.textContent = 'Pembayaran Tunai';
                    kontenMetodePembayaran.innerHTML = `
                        <div class="mb-3">
                            <label class="form-label">Jumlah Bayar</label>
                            <input type="number" class="form-control" placeholder="Masukkan jumlah uang">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kembalian</label>
                            <input type="text" class="form-control" readonly>
                        </div>
                    `;
                    break;
                case 'qris':
                    judulMetodePembayaran.textContent = 'Pembayaran QRIS';
                    kontenMetodePembayaran.innerHTML = `
                        <div class="text-center">
                            <img src="/images/qr-pembayaran.png" alt="QR Pembayaran" class="img-fluid" style="max-width: 200px;">
                            <p class="mt-2">Scan QR Code untuk membayar</p>
                        </div>
                    `;
                    break;
                case 'transfer':
                    judulMetodePembayaran.textContent = 'Transfer Bank';
                    kontenMetodePembayaran.innerHTML = `
                        <div class="mb-3">
                            <label class="form-label">Bank Tujuan</label>
                            <select class="form-select">
                                <option>BCA</option>
                                <option>Mandiri</option>
                                <option>BRI</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" placeholder="Masukkan nomor rekening">
                        </div>
                    `;
                    break;
            }
        });
    });

    tombolBayar.addEventListener('click', function() {
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: 'Apakah Anda yakin ingin menyelesaikan pembayaran?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Pembayaran Berhasil',
                    text: 'Terima kasih!',
                    icon: 'success'
                });
            }
        });
    });
});
</script>
@endpush
