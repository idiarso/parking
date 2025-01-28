<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tarif Parkir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tarif.update', $tarif->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Jenis Kendaraan -->
                        <div>
                            <x-input-label for="jenis_kendaraan" :value="__('Jenis Kendaraan')" />
                            <select 
                                id="jenis_kendaraan" 
                                name="jenis_kendaraan" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option value="motor" {{ $tarif->jenis_kendaraan == 'motor' ? 'selected' : '' }}>Motor</option>
                                <option value="mobil" {{ $tarif->jenis_kendaraan == 'mobil' ? 'selected' : '' }}>Mobil</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_kendaraan')" class="mt-2" />
                        </div>

                        <!-- Tarif per Jam -->
                        <div>
                            <x-input-label for="tarif_per_jam" :value="__('Tarif per Jam')" />
                            <div class="flex items-center">
                                <span class="mr-2">Rp</span>
                                <x-text-input 
                                    id="tarif_per_jam" 
                                    name="tarif_per_jam" 
                                    type="number" 
                                    min="1000" 
                                    max="50000"
                                    class="mt-1 block w-full" 
                                    :value="old('tarif_per_jam', $tarif->tarif_per_jam)"
                                    required 
                                />
                            </div>
                            <x-input-error :messages="$errors->get('tarif_per_jam')" class="mt-2" />
                        </div>

                        <!-- Tarif per Hari -->
                        <div>
                            <x-input-label for="tarif_per_hari" :value="__('Tarif per Hari (Opsional)')" />
                            <div class="flex items-center">
                                <span class="mr-2">Rp</span>
                                <x-text-input 
                                    id="tarif_per_hari" 
                                    name="tarif_per_hari" 
                                    type="number" 
                                    min="10000" 
                                    max="100000"
                                    class="mt-1 block w-full" 
                                    :value="old('tarif_per_hari', $tarif->tarif_per_hari)"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('tarif_per_hari')" class="mt-2" />
                        </div>

                        <!-- Denda per Jam -->
                        <div>
                            <x-input-label for="denda_per_jam" :value="__('Denda per Jam (Opsional)')" />
                            <div class="flex items-center">
                                <span class="mr-2">Rp</span>
                                <x-text-input 
                                    id="denda_per_jam" 
                                    name="denda_per_jam" 
                                    type="number" 
                                    min="1000" 
                                    max="20000"
                                    class="mt-1 block w-full" 
                                    :value="old('denda_per_jam', $tarif->denda_per_jam)"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('denda_per_jam')" class="mt-2" />
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea 
                                id="keterangan" 
                                name="keterangan" 
                                rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >{{ old('keterangan', $tarif->keterangan) }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tarif.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('Perbarui Tarif') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
