<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pintu Masuk Parkir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('pintu-masuk.proses') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plat Nomor</label>
                            <input type="text" name="plat_nomor" 
                                   value="{{ old('plat_nomor') }}"
                                   placeholder="Contoh: B 1234 ABC" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('plat_nomor') border-red-500 @enderror">
                            @error('plat_nomor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slot Parkir</label>
                            <select name="slot_parkir_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('slot_parkir_id') border-red-500 @enderror">
                                <option value="">Pilih Slot Parkir</option>
                                @foreach($slotTersedia as $slot)
                                    <option value="{{ $slot->id }}" 
                                        {{ old('slot_parkir_id') == $slot->id ? 'selected' : '' }}>
                                        Slot {{ $slot->nomor }} ({{ $slot->jenis_kendaraan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('slot_parkir_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kondisi Kendaraan (Opsional)</label>
                            <textarea name="kondisi_kendaraan" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                      rows="3">{{ old('kondisi_kendaraan') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Foto Kendaraan (Opsional)</label>
                            <input type="file" name="foto_kendaraan" 
                                   accept="image/*"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('foto_kendaraan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="submit" class="btn btn-primary">
                            Proses Masuk Kendaraan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
