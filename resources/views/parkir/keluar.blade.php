<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Parkir Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pencarian Kendaraan -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Cari Kendaraan</h3>
                            <form id="cariKendaraanForm" class="space-y-4">
                                @csrf
                                <div>
                                    <x-input-label for="nomor_plat" :value="__('Nomor Plat Kendaraan')" />
                                    <x-text-input 
                                        id="nomor_plat" 
                                        name="nomor_plat" 
                                        type="text" 
                                        class="mt-1 block w-full" 
                                        required 
                                        autofocus 
                                        autocomplete="off" 
                                        placeholder="Contoh: B1234XYZ"
                                    />
                                    <div id="error-nomor-plat" class="text-red-500 mt-2"></div>
                                </div>

                                <x-primary-button type="submit" id="btnCariKendaraan">
                                    {{ __('Cari Kendaraan') }}
                                </x-primary-button>
                            </form>
                        </div>

                        <!-- Detail Kendaraan -->
                        <div id="detailKendaraanContainer" class="hidden">
                            <h3 class="text-lg font-semibold mb-4">Detail Kendaraan</h3>
                            <div id="detailKendaraan" class="bg-gray-100 p-4 rounded-lg space-y-3">
                                <form id="prosesKeluarForm" action="{{ route('parkir.keluar.proses') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="nomor_plat" id="detail-nomor-plat">
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jenis Kendaraan</label>
                                            <p id="detail-jenis-kendaraan" class="mt-1 text-sm text-gray-900"></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Slot Parkir</label>
                                            <p id="detail-slot-parkir" class="mt-1 text-sm text-gray-900"></p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Waktu Masuk</label>
                                            <p id="detail-waktu-masuk" class="mt-1 text-sm text-gray-900"></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Durasi Parkir</label>
                                            <p id="detail-durasi-parkir" class="mt-1 text-sm text-gray-900"></p>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700">Biaya Parkir</label>
                                        <div class="mt-1 flex items-center">
                                            <span class="text-lg font-bold text-green-600" id="detail-biaya-parkir">Rp 0</span>
                                            <input type="hidden" name="biaya_parkir" id="input-biaya-parkir" value="0">
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <x-primary-button type="submit" id="btnProsesKeluar">
                                            {{ __('Proses Keluar') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Kendaraan Parkir -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Kendaraan Sedang Parkir</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Plat</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slot</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Masuk</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($kendaraanParkir as $kendaraan)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $kendaraan->nomor_plat }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ ucfirst($kendaraan->jenis_kendaraan) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $kendaraan->slotParkir->kode_slot ?? 'Tidak ada' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ Carbon\Carbon::parse($kendaraan->waktu_masuk)->format('d M Y H:i:s') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">Tidak ada kendaraan yang sedang parkir</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cariKendaraanForm = document.getElementById('cariKendaraanForm');
            const detailKendaraanContainer = document.getElementById('detailKendaraanContainer');
            const errorNomorPlat = document.getElementById('error-nomor-plat');

            cariKendaraanForm.addEventListener('submit', function(e) {
                e.preventDefault();
                errorNomorPlat.textContent = '';
                detailKendaraanContainer.classList.add('hidden');

                const formData = new FormData(cariKendaraanForm);

                fetch('{{ route('parkir.keluar.cari') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tampilkan detail kendaraan
                        document.getElementById('detail-nomor-plat').value = data.kendaraan.nomor_plat;
                        document.getElementById('detail-jenis-kendaraan').textContent = data.kendaraan.jenis_kendaraan.charAt(0).toUpperCase() + data.kendaraan.jenis_kendaraan.slice(1);
                        document.getElementById('detail-slot-parkir').textContent = data.kendaraan.slot_parkir ? data.kendaraan.slot_parkir.kode_slot : 'Tidak ada';
                        document.getElementById('detail-waktu-masuk').textContent = new Date(data.kendaraan.waktu_masuk).toLocaleString();
                        document.getElementById('detail-durasi-parkir').textContent = `${Math.ceil(data.durasi_parkir / 60)} jam`;
                        
                        const biayaParkir = data.biaya_parkir;
                        document.getElementById('detail-biaya-parkir').textContent = `Rp ${biayaParkir.toLocaleString('id-ID')}`;
                        document.getElementById('input-biaya-parkir').value = biayaParkir;

                        detailKendaraanContainer.classList.remove('hidden');
                    } else {
                        errorNomorPlat.textContent = data.message;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorNomorPlat.textContent = 'Terjadi kesalahan saat mencari kendaraan';
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
