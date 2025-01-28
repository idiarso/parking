<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Tarif Parkir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left font-bold bg-gray-200">
                                    <th class="px-4 py-3">Jenis Kendaraan</th>
                                    <th class="px-4 py-3">Tarif per Jam</th>
                                    <th class="px-4 py-3">Tarif per Hari</th>
                                    <th class="px-4 py-3">Denda per Jam</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Dibuat</th>
                                    <th class="px-4 py-3">Dihapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatTarif as $item)
                                    <tr class="{{ $item->aktif ? 'bg-green-50' : 'bg-gray-50' }} hover:bg-gray-100">
                                        <td class="px-4 py-3">
                                            {{ ucfirst($item->jenis_kendaraan) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            Rp {{ number_format($item->tarif_per_jam, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            Rp {{ number_format($item->tarif_per_hari, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            Rp {{ number_format($item->denda_per_jam, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="{{ $item->aktif ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $item->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $item->created_at->translatedFormat('d F Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $item->deleted_at ? $item->deleted_at->translatedFormat('d F Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                            Tidak ada riwayat tarif parkir
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-right">
                <a href="{{ route('tarif.index') }}" class="text-blue-500 hover:text-blue-700">
                    Kembali ke Daftar Tarif
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
