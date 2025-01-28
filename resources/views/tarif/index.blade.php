<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Tarif Parkir') }}
            </h2>
            <a href="{{ route('tarif.create') }}" class="btn btn-primary">
                {{ __('Tambah Tarif Baru') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

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
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tarif as $item)
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
                                            <div class="flex space-x-2">
                                                <a href="{{ route('tarif.edit', $item->id) }}" 
                                                   class="text-blue-500 hover:text-blue-700">
                                                    Edit
                                                </a>
                                                
                                                @if($item->aktif)
                                                    <form action="{{ route('tarif.nonaktifkan', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                                            Nonaktifkan
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('tarif.aktifkan', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-500 hover:text-green-700">
                                                            Aktifkan
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('tarif.destroy', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus tarif ini?')"
                                                        class="text-red-500 hover:text-red-700">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                            Tidak ada tarif parkir
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-right">
                <a href="{{ route('tarif.riwayat') }}" class="text-blue-500 hover:text-blue-700">
                    Lihat Riwayat Tarif
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
