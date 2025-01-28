<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pintu Keluar Parkir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('pintu-keluar.verifikasi') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plat Nomor Kendaraan</label>
                            <input type="text" name="plat_nomor" 
                                   value="{{ old('plat_nomor') }}"
                                   placeholder="Contoh: B 1234 ABC" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('plat_nomor') border-red-500 @enderror">
                            @error('plat_nomor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="submit" class="btn btn-primary">
                            Verifikasi Kendaraan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
