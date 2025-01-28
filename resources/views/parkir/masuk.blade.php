<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Parkir Masuk') }}
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

                    <form action="{{ route('parkir.masuk.proses') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="nomor_plat" :value="__('Nomor Plat')" />
                            <x-text-input id="nomor_plat" name="nomor_plat" type="text" class="mt-1 block w-full" 
                                required autofocus autocomplete="off" placeholder="Contoh: B1234XYZ" 
                                value="{{ old('nomor_plat') }}" />
                            <x-input-error :messages="$errors->get('nomor_plat')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="jenis_kendaraan" :value="__('Jenis Kendaraan')" />
                            <select id="jenis_kendaraan" name="jenis_kendaraan" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                required>
                                <option value="">Pilih Jenis Kendaraan</option>
                                <option value="motor" {{ old('jenis_kendaraan') == 'motor' ? 'selected' : '' }}>Motor</option>
                                <option value="mobil" {{ old('jenis_kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_kendaraan')" class="mt-2" />
                            
                            <div id="slot-info" class="mt-2 text-sm text-gray-600">
                                Slot tersedia: 
                                <span id="slot-tersedia">-</span>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="pemilik" :value="__('Nama Pemilik')" />
                            <x-text-input id="pemilik" name="pemilik" type="text" class="mt-1 block w-full" 
                                autocomplete="off" placeholder="Nama Pemilik Kendaraan" 
                                value="{{ old('pemilik') }}" />
                            <x-input-error :messages="$errors->get('pemilik')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="merk" :value="__('Merk Kendaraan')" />
                                <x-text-input id="merk" name="merk" type="text" class="mt-1 block w-full" 
                                    autocomplete="off" placeholder="Merk Kendaraan" 
                                    value="{{ old('merk') }}" />
                                <x-input-error :messages="$errors->get('merk')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="warna" :value="__('Warna Kendaraan')" />
                                <x-text-input id="warna" name="warna" type="text" class="mt-1 block w-full" 
                                    autocomplete="off" placeholder="Warna Kendaraan" 
                                    value="{{ old('warna') }}" />
                                <x-input-error :messages="$errors->get('warna')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Proses Parkir Masuk') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisKendaraanSelect = document.getElementById('jenis_kendaraan');
            const slotTersediaSpan = document.getElementById('slot-tersedia');

            function cekSlotTersedia() {
                const jenisKendaraan = jenisKendaraanSelect.value;
                
                if (jenisKendaraan) {
                    fetch('{{ route('parkir.slot.cek') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ jenis_kendaraan: jenisKendaraan })
                    })
                    .then(response => response.json())
                    .then(data => {
                        slotTersediaSpan.textContent = data.slot_tersedia;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        slotTersediaSpan.textContent = '-';
                    });
                } else {
                    slotTersediaSpan.textContent = '-';
                }
            }

            jenisKendaraanSelect.addEventListener('change', cekSlotTersedia);
        });
    </script>
    @endpush
</x-app-layout>
