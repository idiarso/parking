<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Kendaraan') }}
            </h2>
            <a href="{{ route('kendaraan.create') }}" class="btn btn-primary">
                Tambah Kendaraan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('kendaraan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plat Nomor</label>
                        <input type="text" name="plat_nomor" value="{{ request('plat_nomor') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Semua Jenis</option>
                            <option value="motor" {{ request('jenis_kendaraan') == 'motor' ? 'selected' : '' }}>Motor</option>
                            <option value="mobil" {{ request('jenis_kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="parkir" {{ request('status') == 'parkir' ? 'selected' : '' }}>Parkir</option>
                            <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Tabel Kendaraan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Plat Nomor</th>
                                    <th scope="col" class="px-6 py-3">Jenis Kendaraan</th>
                                    <th scope="col" class="px-6 py-3">Waktu Masuk</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kendaraan as $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $item->plat_nomor }}</td>
                                    <td class="px-6 py-4">
                                        <span class="badge {{ $item->jenis_kendaraan == 'motor' ? 'badge-info' : 'badge-warning' }}">
                                            {{ ucfirst($item->jenis_kendaraan) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $item->waktu_masuk->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="badge {{ $item->status == 'parkir' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <a href="{{ route('kendaraan.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <form action="{{ route('kendaraan.destroy', $item->id) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus data kendaraan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">
                                        Tidak ada data kendaraan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $kendaraan->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

            <!-- Tombol Riwayat -->
            <div class="mt-4 text-right">
                <a href="{{ route('kendaraan.riwayat') }}" class="btn btn-secondary">
                    Lihat Riwayat Kendaraan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
