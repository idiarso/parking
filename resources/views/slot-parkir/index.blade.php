<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Slot Parkir') }}
            </h2>
            <a href="{{ route('slot-parkir.create') }}" class="btn btn-primary">
                Tambah Slot Parkir
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-2">Total Slot Tersedia</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $statusTersedia }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-2">Slot Motor Tersedia</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $statusMotor }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-2">Slot Mobil Tersedia</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $statusMobil }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('slot-parkir.index') }}" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kendaraan</label>
                                <select name="jenis_kendaraan" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Semua Jenis</option>
                                    <option value="motor" {{ request('jenis_kendaraan') == 'motor' ? 'selected' : '' }}>Motor</option>
                                    <option value="mobil" {{ request('jenis_kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Semua Status</option>
                                    <option value="kosong" {{ request('status') == 'kosong' ? 'selected' : '' }}>Kosong</option>
                                    <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                    <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left font-bold bg-gray-100">
                                    <th class="px-4 py-3">Nomor Slot</th>
                                    <th class="px-4 py-3">Jenis Kendaraan</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Lokasi</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slotParkir as $slot)
                                    <tr class="border-b hover:bg-gray-100">
                                        <td class="px-4 py-3">{{ $slot->nomor }}</td>
                                        <td class="px-4 py-3">
                                            <span class="badge {{ $slot->jenis_kendaraan == 'motor' ? 'badge-blue' : 'badge-indigo' }}">
                                                {{ ucfirst($slot->jenis_kendaraan) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge {{ 
                                                $slot->status == 'kosong' ? 'badge-green' : 
                                                ($slot->status == 'terisi' ? 'badge-red' : 
                                                ($slot->status == 'rusak' ? 'badge-yellow' : 'badge-gray')) 
                                            }}">
                                                {{ ucfirst($slot->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ $slot->lokasi ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('slot-parkir.edit', $slot) }}" class="btn btn-sm btn-secondary">
                                                    Edit
                                                </a>
                                                <form action="{{ route('slot-parkir.destroy', $slot) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus slot ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Tidak ada slot parkir</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $slotParkir->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
