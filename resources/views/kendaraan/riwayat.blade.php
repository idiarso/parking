<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Kendaraan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Plat Nomor</th>
                                    <th scope="col" class="px-6 py-3">Jenis Kendaraan</th>
                                    <th scope="col" class="px-6 py-3">Waktu Masuk</th>
                                    <th scope="col" class="px-6 py-3">Waktu Keluar</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Durasi</th>
                                    <th scope="col" class="px-6 py-3">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatKendaraan as $item)
                                <tr class="bg-white border-b hover:bg-gray-50 {{ $item->trashed() ? 'bg-gray-100' : '' }}">
                                    <td class="px-6 py-4">{{ $item->plat_nomor }}</td>
                                    <td class="px-6 py-4">
                                        <span class="badge {{ $item->jenis_kendaraan == 'motor' ? 'badge-info' : 'badge-warning' }}">
                                            {{ ucfirst($item->jenis_kendaraan) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $item->waktu_masuk->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        {{ $item->waktu_keluar ? $item->waktu_keluar->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="badge {{ $item->status == 'parkir' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ ucfirst($item->status) }}
                                            @if($item->trashed())
                                                (Dihapus)
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->durasi_parkir ? $item->durasi_parkir_format : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->biaya_parkir ? $item->biaya_parkir_format : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center">
                                        Tidak ada riwayat kendaraan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $riwayatKendaraan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
