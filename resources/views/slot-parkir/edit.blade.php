<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Slot Parkir') }}: {{ $slotParkir->nomor }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('slot-parkir.update', $slotParkir) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Slot</label>
                            <input type="text" name="nomor" 
                                   value="{{ old('nomor', $slotParkir->nomor) }}"
                                   placeholder="Contoh: A1, B2" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('nomor') border-red-500 @enderror">
                            @error('nomor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kendaraan</label>
                            <select name="jenis_kendaraan" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('jenis_kendaraan') border-red-500 @enderror">
                                <option value="">Pilih Jenis Kendaraan</option>
                                <option value="motor" {{ old('jenis_kendaraan', $slotParkir->jenis_kendaraan) == 'motor' ? 'selected' : '' }}>Motor</option>
                                <option value="mobil" {{ old('jenis_kendaraan', $slotParkir->jenis_kendaraan) == 'mobil' ? 'selected' : '' }}>Mobil</option>
                            </select>
                            @error('jenis_kendaraan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('status') border-red-500 @enderror">
                                <option value="kosong" {{ old('status', $slotParkir->status) == 'kosong' ? 'selected' : '' }}>Kosong</option>
                                <option value="terisi" {{ old('status', $slotParkir->status) == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                <option value="rusak" {{ old('status', $slotParkir->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="maintenance" {{ old('status', $slotParkir->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi (Opsional)</label>
                            <input type="text" name="lokasi" 
                                   value="{{ old('lokasi', $slotParkir->lokasi) }}"
                                   placeholder="Contoh: Gedung A Lantai 1" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                            <textarea name="keterangan" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                      rows="3">{{ old('keterangan', $slotParkir->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('slot-parkir.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Perbarui Slot Parkir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
