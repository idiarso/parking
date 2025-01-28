<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Pembayaran Parkir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Detail Kendaraan</h3>
                        <table class="w-full">
                            <tr>
                                <td class="py-2">Plat Nomor</td>
                                <td>: {{ $kendaraan->plat_nomor }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">Jenis Kendaraan</td>
                                <td>: {{ ucfirst($kendaraan->jenis_kendaraan) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">Waktu Masuk</td>
                                <td>: {{ $kendaraan->waktu_masuk->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">Waktu Keluar</td>
                                <td>: {{ $kendaraan->waktu_keluar->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Rincian Biaya</h3>
                        <table class="w-full">
                            <tr>
                                <td class="py-2">Durasi Parkir</td>
                                <td>: {{ $kendaraan->durasi_parkir }} Jam</td>
                            </tr>
                            <tr>
                                <td class="py-2">Tarif Per Jam</td>
                                <td>: Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">Total Biaya</td>
                                <td>: <strong>Rp {{ number_format($kendaraan->biaya_parkir, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-6">
                    <form action="{{ route('pintu-keluar.bayar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kendaraan_id" value="{{ $kendaraan->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Pilih Metode Pembayaran</label>
                            <div class="mt-2 space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="metode_pembayaran" value="tunai" 
                                           class="form-radio" required>
                                    <span class="ml-2">Tunai</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="metode_pembayaran" value="transfer" 
                                           class="form-radio">
                                    <span class="ml-2">Transfer</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="metode_pembayaran" value="qris" 
                                           class="form-radio">
                                    <span class="ml-2">QRIS</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('pintu-keluar.index') }}" class="btn btn-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Bayar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
