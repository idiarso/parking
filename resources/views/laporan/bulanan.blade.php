<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Parkir Bulanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">
                            Laporan Parkir - {{ Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}
                        </h3>
                        <form action="{{ route('laporan.bulanan.cetak') }}" method="GET">
                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                            <x-primary-button>
                                {{ __('Cetak PDF') }}
                            </x-primary-button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600">Total Kendaraan</h4>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalKendaraan }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600">Total Pendapatan</h4>
                            <p class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600">Kendaraan per Jenis</h4>
                            <div class="flex space-x-4">
                                @foreach($kendaraanPerJenis as $jenis => $jumlah)
                                    <div>
                                        <span class="text-sm text-gray-600">{{ ucfirst($jenis) }}:</span>
                                        <span class="font-bold">{{ $jumlah }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Pendapatan Harian -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-4">Pendapatan Harian</h4>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <canvas id="pendapatanHarianChart"></canvas>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Plat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Masuk</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Keluar</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($laporanBulanan as $kendaraan)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $kendaraan->nomor_plat }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ ucfirst($kendaraan->jenis_kendaraan) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ Carbon\Carbon::parse($kendaraan->waktu_masuk)->format('d M H:i:s') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $kendaraan->waktu_keluar ? Carbon\Carbon::parse($kendaraan->waktu_keluar)->format('d M H:i:s') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        Rp {{ number_format($kendaraan->biaya_parkir ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                        Tidak ada data kendaraan untuk bulan ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pendapatanHarian = @json($pendapatanHarian);
            const ctx = document.getElementById('pendapatanHarianChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(pendapatanHarian),
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: Object.values(pendapatanHarian),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Pendapatan (Rp)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
