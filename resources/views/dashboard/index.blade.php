<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Parkir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Total Kendaraan Hari Ini</h3>
                    <div class="text-3xl font-bold text-blue-600">{{ $totalKendaraan }}</div>
                    <div class="mt-2">
                        @foreach($kendaraanPerJenis as $jenis => $jumlah)
                            <span class="text-sm text-gray-600">{{ ucfirst($jenis) }}: {{ $jumlah }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Status Slot Parkir</h3>
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ $slotKosong }}</div>
                            <div class="text-sm text-gray-600">Slot Kosong</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600">{{ $slotTerisi }}</div>
                            <div class="text-sm text-gray-600">Slot Terisi</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div 
                                class="bg-blue-600 h-2.5 rounded-full" 
                                style="width: {{ ($slotTerisi / $totalSlot) * 100 }}%"
                            ></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Pendapatan Hari Ini</h3>
                    <div class="text-3xl font-bold text-green-600">
                        Rp {{ number_format($pendapatanHarian, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Grafik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Grafik Pendapatan Mingguan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Pendapatan Mingguan</h3>
                    <canvas id="pendapatanMingguanChart"></canvas>
                </div>

                <!-- Grafik Okupansi Parkir -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Okupansi Parkir Harian</h3>
                    <canvas id="okupasiParkirChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Pendapatan Mingguan
            const pendapatanData = @json($pendapatanMingguan);
            const ctxPendapatan = document.getElementById('pendapatanMingguanChart').getContext('2d');
            
            new Chart(ctxPendapatan, {
                type: 'bar',
                data: {
                    labels: Object.keys(pendapatanData),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: Object.values(pendapatanData),
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
                        }
                    }
                }
            });

            // Grafik Okupansi Parkir
            const okupasiData = @json($okupasiParkir);
            const ctxOkupasi = document.getElementById('okupasiParkirChart').getContext('2d');
            
            new Chart(ctxOkupasi, {
                type: 'line',
                data: {
                    labels: Object.keys(okupasiData),
                    datasets: [{
                        label: 'Jumlah Kendaraan',
                        data: Object.values(okupasiData),
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Jam'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Kendaraan'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
