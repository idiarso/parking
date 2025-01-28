<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Parkir\KendaraanController;
use App\Http\Controllers\Parkir\SlotParkirController;
use App\Http\Controllers\Parkir\TarifParkirController;
use App\Http\Controllers\Parkir\LaporanController;
use App\Http\Controllers\Laporan\LaporanController as LaporanBaru;
use App\Http\Controllers\Parkir\TestController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ParkirController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\SimulasiController;
use App\Http\Controllers\InfrastrukturController;
use App\Http\Controllers\KustomisasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ManajemenPerangkatController;
use App\Http\Controllers\ManajemenPenggunaController;
use App\Http\Controllers\SimulasiParkirController;
use App\Http\Controllers\ParkirMasukController;
use App\Http\Controllers\ParkirKeluarController;
use App\Http\Controllers\PintuMasukController;
use App\Http\Controllers\PintuKeluarController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Kendaraan
    Route::resource('kendaraan', KendaraanController::class)
        ->middleware(['auth', 'verified']);

    Route::get('/kendaraan/riwayat', [KendaraanController::class, 'riwayat'])
        ->name('kendaraan.riwayat')
        ->middleware(['auth', 'verified']);

    // Rute untuk Slot Parkir
    Route::prefix('slot-parkir')->group(function () {
        Route::get('/', [SlotParkirController::class, 'index'])
            ->name('slot-parkir.index')
            ->middleware(['auth', 'verified']);
        
        Route::get('/create', [SlotParkirController::class, 'create'])
            ->name('slot-parkir.create')
            ->middleware(['auth', 'verified']);
        
        Route::post('/', [SlotParkirController::class, 'store'])
            ->name('slot-parkir.store')
            ->middleware(['auth', 'verified']);
        
        Route::get('/{slotParkir}/edit', [SlotParkirController::class, 'edit'])
            ->name('slot-parkir.edit')
            ->middleware(['auth', 'verified']);
        
        Route::put('/{slotParkir}', [SlotParkirController::class, 'update'])
            ->name('slot-parkir.update')
            ->middleware(['auth', 'verified']);
        
        Route::delete('/{slotParkir}', [SlotParkirController::class, 'destroy'])
            ->name('slot-parkir.destroy')
            ->middleware(['auth', 'verified']);
        
        Route::post('/{slotParkir}/status', [SlotParkirController::class, 'ubahStatus'])
            ->name('slot-parkir.ubah-status')
            ->middleware(['auth', 'verified']);
        
        Route::get('/kosong', [SlotParkirController::class, 'slotKosong'])->name('slot-parkir.kosong');
        Route::post('/', [SlotParkirController::class, 'buatSlot'])->name('slot-parkir.buat');
        Route::put('/{id}', [SlotParkirController::class, 'updateSlot'])->name('slot-parkir.update');
        Route::delete('/{id}', [SlotParkirController::class, 'hapusSlot'])->name('slot-parkir.hapus');
    });

    // Rute untuk Tarif Parkir
    Route::prefix('tarif-parkir')->group(function () {
        Route::get('/', [TarifParkirController::class, 'daftarTarif'])->name('tarif-parkir.index');
        Route::get('/aktif', [TarifParkirController::class, 'tarifAktif'])->name('tarif-parkir.aktif');
        Route::post('/', [TarifParkirController::class, 'tambahTarif'])->name('tarif-parkir.tambah');
        Route::put('/{id}', [TarifParkirController::class, 'updateTarif'])->name('tarif-parkir.update');
        Route::delete('/{id}', [TarifParkirController::class, 'hapusTarif'])->name('tarif-parkir.hapus');
    });

    // Rute untuk Laporan
    Route::prefix('laporan')->middleware(['auth', 'verified'])->group(function () {
        // Laporan Utama
        Route::get('/', [LaporanBaru::class, 'index'])
            ->name('laporan.index');
        
        // Laporan Harian
        Route::get('/harian', [LaporanBaru::class, 'laporanHarian'])
            ->name('laporan.harian');
        
        Route::get('/harian/cetak', [LaporanBaru::class, 'cetakLaporanHarian'])
            ->name('laporan.harian.cetak');
        
        // Laporan Bulanan
        Route::get('/bulanan', [LaporanBaru::class, 'laporanBulanan'])
            ->name('laporan.bulanan');
        
        Route::get('/bulanan/cetak', [LaporanBaru::class, 'cetakLaporanBulanan'])
            ->name('laporan.bulanan.cetak');
    });

    // Test Routes
    Route::prefix('test')->group(function () {
        Route::get('/masuk-kendaraan/{jenisKendaraan?}', [TestController::class, 'simulasiMasuk'])->name('test.masuk');
        Route::get('/keluar-kendaraan/{id}', [TestController::class, 'simulasiKeluar'])->name('test.keluar');
        Route::get('/keluar-semua', [TestController::class, 'simulasiKeluarSemua'])->name('test.keluar-semua');
    });

    // Pintu Masuk dan Keluar Kendaraan
    Route::prefix('parkir')->group(function () {
        Route::get('/masuk', [ParkirController::class, 'masuk'])->name('parkir.masuk');
        Route::get('/keluar', [ParkirController::class, 'keluar'])->name('parkir.keluar');
        Route::post('/proses-masuk', [ParkirController::class, 'prosesMasuk'])->name('parkir.proses-masuk');
        Route::post('/proses-keluar', [ParkirController::class, 'prosesKeluar'])->name('parkir.proses-keluar');
    });

    // Parkir Masuk
    Route::prefix('parkir')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/masuk', [ParkirMasukController::class, 'index'])
            ->name('parkir.masuk');
        
        Route::post('/masuk', [ParkirMasukController::class, 'prosesParkirMasuk'])
            ->name('parkir.masuk.proses');
        
        Route::post('/slot/cek', [ParkirMasukController::class, 'cekSlotTersedia'])
            ->name('parkir.slot.cek');
    });

    // Parkir Keluar
    Route::prefix('parkir')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/keluar', [ParkirKeluarController::class, 'index'])
            ->name('parkir.keluar');
        
        Route::post('/keluar/cari', [ParkirKeluarController::class, 'cariKendaraan'])
            ->name('parkir.keluar.cari');
        
        Route::post('/keluar/proses', [ParkirKeluarController::class, 'prosesParkirKeluar'])
            ->name('parkir.keluar.proses');
    });

    // Rute untuk Pintu Masuk
    Route::prefix('pintu-masuk')->group(function () {
        Route::get('/', [PintuMasukController::class, 'index'])
            ->name('pintu-masuk.index')
            ->middleware(['auth', 'verified']);
        
        Route::post('/proses', [PintuMasukController::class, 'prosesKendaraanMasuk'])
            ->name('pintu-masuk.proses')
            ->middleware(['auth', 'verified']);
    });

    // Rute untuk Pintu Keluar
    Route::prefix('pintu-keluar')->group(function () {
        Route::get('/', [PintuKeluarController::class, 'index'])
            ->name('pintu-keluar.index')
            ->middleware(['auth', 'verified']);
        
        Route::post('/verifikasi', [PintuKeluarController::class, 'verifikasiKendaraan'])
            ->name('pintu-keluar.verifikasi')
            ->middleware(['auth', 'verified']);
        
        Route::post('/bayar', [PintuKeluarController::class, 'prosesPembayaran'])
            ->name('pintu-keluar.bayar')
            ->middleware(['auth', 'verified']);
    });

    // Menu Utama
    Route::middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/menu/dashboard', [MenuController::class, 'dashboard'])->name('menu.dashboard');
        
        // Manajemen Kendaraan
        Route::prefix('menu/kendaraan')->group(function () {
            Route::get('/', [MenuController::class, 'manajemenKendaraan'])->name('menu.kendaraan.index');
            Route::get('/tambah', [MenuController::class, 'tambahKendaraan'])->name('menu.kendaraan.tambah');
            Route::post('/simpan', [MenuController::class, 'simpanKendaraan'])->name('menu.kendaraan.simpan');
            Route::get('/edit/{id}', [MenuController::class, 'editKendaraan'])->name('menu.kendaraan.edit');
            Route::put('/update/{id}', [MenuController::class, 'updateKendaraan'])->name('menu.kendaraan.update');
            Route::delete('/hapus/{id}', [MenuController::class, 'hapusKendaraan'])->name('menu.kendaraan.hapus');
        });

        // Sistem Parkir
        Route::prefix('menu/parkir')->group(function () {
            Route::get('/', [MenuController::class, 'sistemParkir'])->name('menu.parkir.sistem');
            Route::get('/masuk', [MenuController::class, 'pintuMasuk'])->name('menu.parkir.masuk');
            Route::get('/keluar', [MenuController::class, 'pintuKeluar'])->name('menu.parkir.keluar');
            
            // Rute Simulasi untuk Testing
            Route::get('/simulasi-masuk', [TestController::class, 'simulasiMasuk'])->name('menu.parkir.simulasi.masuk');
            Route::get('/simulasi-keluar', [TestController::class, 'simulasiKeluar'])->name('menu.parkir.simulasi.keluar');
        });

        // Manajemen Slot
        Route::prefix('menu/slot')->group(function () {
            Route::get('/', [MenuController::class, 'manajemenSlot'])->name('menu.slot.index');
            Route::get('/status', [MenuController::class, 'statusSlot'])->name('menu.slot.status');
        });

        // Laporan
        Route::prefix('menu/laporan')->group(function () {
            Route::get('/', [MenuController::class, 'laporan'])->name('menu.laporan.index');
            Route::get('/harian', [MenuController::class, 'laporanHarian'])->name('menu.laporan.harian');
            Route::get('/bulanan', [MenuController::class, 'laporanBulanan'])->name('menu.laporan.bulanan');
            Route::get('/pendapatan', [MenuController::class, 'laporanPendapatan'])->name('menu.laporan.pendapatan');
        });

        // Pengaturan
        Route::prefix('menu/pengaturan')->group(function () {
            Route::get('/', [MenuController::class, 'pengaturan'])->name('menu.pengaturan.index');
            Route::get('/tarif', [MenuController::class, 'pengaturanTarif'])->name('menu.pengaturan.tarif');
            Route::get('/pengguna', [MenuController::class, 'manajemenPengguna'])->name('menu.pengaturan.pengguna');
        });

        // Keamanan Sistem
        Route::prefix('menu/keamanan')->group(function () {
            Route::get('/', [MenuController::class, 'keamananSistem'])->name('menu.keamanan.index');
            Route::get('/log-aktivitas', [MenuController::class, 'logAktivitas'])->name('menu.keamanan.log');
        });
    });

    // Monitoring
    Route::prefix('monitoring')->group(function () {
        Route::get('/', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/realtime', [MonitoringController::class, 'getRealtimeData'])->name('monitoring.realtime');
    });

    // Simulasi
    Route::prefix('simulasi')->group(function () {
        Route::get('/', [SimulasiController::class, 'index'])->name('simulasi.index');
        Route::post('/hitung', [SimulasiController::class, 'simulasiParkir'])->name('simulasi.hitung');
    });

    // Simulasi Parkir
    Route::prefix('simulasi-parkir')->group(function () {
        Route::get('/', [SimulasiParkirController::class, 'index'])->name('simulasi-parkir.index');
        Route::post('/buat', [SimulasiParkirController::class, 'buatSimulasi'])->name('simulasi-parkir.buat');
        Route::post('/{simulasiId}/jalankan', [SimulasiParkirController::class, 'jalankanSimulasi'])->name('simulasi-parkir.jalankan');
        Route::post('/skenario/tambah', [SimulasiParkirController::class, 'tambahSkenario'])->name('simulasi-parkir.skenario.tambah');
        Route::post('/skenario/analisis', [SimulasiParkirController::class, 'analisisKomparatif'])->name('simulasi-parkir.skenario.analisis');
        Route::get('/daftar', [SimulasiParkirController::class, 'daftarSimulasi'])->name('simulasi-parkir.daftar');
        Route::get('/{simulasiId}', [SimulasiParkirController::class, 'detailSimulasi'])->name('simulasi-parkir.detail');
    });

    // Infrastruktur
    Route::prefix('infrastruktur')->group(function () {
        Route::get('/', [InfrastrukturController::class, 'index'])->name('infrastruktur.index');
        Route::post('/pemeliharaan', [InfrastrukturController::class, 'jadwalkanPemeliharaan'])->name('infrastruktur.pemeliharaan');
        Route::post('/laporkan-masalah', [InfrastrukturController::class, 'laporkanMasalah'])->name('infrastruktur.laporkan-masalah');
        Route::post('/restart', [InfrastrukturController::class, 'restartSistem'])->name('infrastruktur.restart');
    });

    // Kustomisasi
    Route::prefix('kustomisasi')->group(function () {
        Route::get('/', [KustomisasiController::class, 'index'])->name('kustomisasi.index');
        Route::post('/tema/aktifkan', [KustomisasiController::class, 'aktifkanTema'])->name('kustomisasi.tema');
        Route::post('/komponen/toggle', [KustomisasiController::class, 'toggleKomponen'])->name('kustomisasi.komponen');
        Route::post('/aksesibilitas/toggle', [KustomisasiController::class, 'toggleAksesibilitas'])->name('kustomisasi.aksesibilitas');
        Route::post('/preferensi/simpan', [KustomisasiController::class, 'simpanPreferensi'])->name('kustomisasi.preferensi');
    });

    // Pembayaran
    Route::prefix('pembayaran')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::post('/proses-keluar', [PembayaranController::class, 'prosesKeluar'])->name('pembayaran.proses-keluar');
        Route::get('/cetak-struk/{pembayaranId}', [PembayaranController::class, 'cetakStruk'])->name('pembayaran.cetak-struk');
        Route::get('/metode', [PembayaranController::class, 'metodePembayaran'])->name('pembayaran.metode');
        Route::get('/laporan', [PembayaranController::class, 'laporan'])->name('pembayaran.laporan');
    });

    // Manajemen Perangkat
    Route::prefix('manajemen-perangkat')->group(function () {
        Route::get('/', [ManajemenPerangkatController::class, 'index'])->name('manajemen-perangkat.index');
        Route::post('/tambah', [ManajemenPerangkatController::class, 'tambahPerangkat'])->name('manajemen-perangkat.tambah');
        Route::put('/{perangkatId}/status', [ManajemenPerangkatController::class, 'updateStatus'])->name('manajemen-perangkat.update-status');
        Route::get('/{perangkatId}/kesehatan', [ManajemenPerangkatController::class, 'periksaKesehatan'])->name('manajemen-perangkat.kesehatan');
        Route::post('/{perangkatId}/pemeliharaan', [ManajemenPerangkatController::class, 'catatPemeliharaan'])->name('manajemen-perangkat.pemeliharaan');
        Route::get('/daftar', [ManajemenPerangkatController::class, 'daftarPerangkat'])->name('manajemen-perangkat.daftar');
    });

    // Manajemen Pengguna
    Route::prefix('manajemen-pengguna')->group(function () {
        Route::get('/', [ManajemenPenggunaController::class, 'index'])->name('manajemen-pengguna.index');
        Route::post('/tambah', [ManajemenPenggunaController::class, 'tambahPengguna'])->name('manajemen-pengguna.tambah');
        Route::put('/{userId}', [ManajemenPenggunaController::class, 'updatePengguna'])->name('manajemen-pengguna.update');
        Route::delete('/{userId}', [ManajemenPenggunaController::class, 'hapusPengguna'])->name('manajemen-pengguna.hapus');
        Route::post('/{userId}/reset-password', [ManajemenPenggunaController::class, 'resetPassword'])->name('manajemen-pengguna.reset-password');
        Route::get('/{userId}/riwayat-login', [ManajemenPenggunaController::class, 'riwayatLogin'])->name('manajemen-pengguna.riwayat-login');
    });

    // Manajemen Tarif
    Route::prefix('tarif')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/', [TarifParkirController::class, 'index'])->name('tarif.index');
        Route::get('/create', [TarifParkirController::class, 'create'])->name('tarif.create');
        Route::post('/store', [TarifParkirController::class, 'store'])->name('tarif.store');
        Route::get('/{id}/edit', [TarifParkirController::class, 'edit'])->name('tarif.edit');
        Route::put('/{id}', [TarifParkirController::class, 'update'])->name('tarif.update');
        Route::delete('/{id}', [TarifParkirController::class, 'destroy'])->name('tarif.destroy');
        Route::post('/{id}/nonaktifkan', [TarifParkirController::class, 'nonaktifkan'])->name('tarif.nonaktifkan');
        Route::post('/{id}/aktifkan', [TarifParkirController::class, 'aktifkan'])->name('tarif.aktifkan');
        Route::get('/riwayat', [TarifParkirController::class, 'riwayatTarif'])->name('tarif.riwayat');
    });
});

require __DIR__.'/auth.php';
