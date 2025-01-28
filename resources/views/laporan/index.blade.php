<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Parkir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Laporan Harian -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Laporan Harian</h3>
                        <form action="{{ route('laporan.harian') }}" method="GET" class="space-y-4">
                            <div>
                                <x-input-label for="tanggal_harian" :value="__('Pilih Tanggal')" />
                                <x-text-input 
                                    id="tanggal_harian" 
                                    name="tanggal" 
                                    type="date" 
                                    class="mt-1 block w-full" 
                                    value="{{ now()->format('Y-m-d') }}"
                                />
                            </div>
                            <div class="flex space-x-4">
                                <x-primary-button type="submit" name="view">
                                    {{ __('Lihat Laporan') }}
                                </x-primary-button>
                                <x-primary-button type="submit" name="cetak" formaction="{{ route('laporan.harian.cetak') }}">
                                    {{ __('Cetak PDF') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Laporan Bulanan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Laporan Bulanan</h3>
                        <form action="{{ route('laporan.bulanan') }}" method="GET" class="space-y-4">
                            <div>
                                <x-input-label for="bulan" :value="__('Pilih Bulan')" />
                                <x-text-input 
                                    id="bulan" 
                                    name="bulan" 
                                    type="month" 
                                    class="mt-1 block w-full" 
                                    value="{{ now()->format('Y-m') }}"
                                />
                            </div>
                            <div class="flex space-x-4">
                                <x-primary-button type="submit" name="view">
                                    {{ __('Lihat Laporan') }}
                                </x-primary-button>
                                <x-primary-button type="submit" name="cetak" formaction="{{ route('laporan.bulanan.cetak') }}">
                                    {{ __('Cetak PDF') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
