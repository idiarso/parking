<?php

namespace App\Models\Parkir;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'tanggal',
        'jenis_laporan',
        'total_kendaraan',
        'total_pendapatan',
        'detail_laporan'
    ];

    protected $casts = [
        'detail_laporan' => 'array',
        'tanggal' => 'date'
    ];

    // Scope untuk jenis laporan
    public function scopeJenis($query, $jenisLaporan)
    {
        return $query->where('jenis_laporan', $jenisLaporan);
    }

    // Metode untuk membuat laporan harian
    public static function buatLaporanHarian()
    {
        $totalKendaraan = Kendaraan::whereDate('waktu_keluar', today())->count();
        $totalPendapatan = Kendaraan::whereDate('waktu_keluar', today())->sum('biaya_parkir');

        return self::create([
            'tanggal' => today(),
            'jenis_laporan' => 'harian',
            'total_kendaraan' => $totalKendaraan,
            'total_pendapatan' => $totalPendapatan,
            'detail_laporan' => [
                'motor' => Kendaraan::whereDate('waktu_keluar', today())
                    ->where('jenis_kendaraan', 'motor')
                    ->count(),
                'mobil' => Kendaraan::whereDate('waktu_keluar', today())
                    ->where('jenis_kendaraan', 'mobil')
                    ->count()
            ]
        ]);
    }

    // Metode untuk membuat laporan bulanan
    public static function buatLaporanBulanan($bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? now()->month;
        $tahun = $tahun ?? now()->year;

        $totalKendaraan = Kendaraan::whereMonth('waktu_keluar', $bulan)
            ->whereYear('waktu_keluar', $tahun)
            ->count();

        $totalPendapatan = Kendaraan::whereMonth('waktu_keluar', $bulan)
            ->whereYear('waktu_keluar', $tahun)
            ->sum('biaya_parkir');

        return self::create([
            'tanggal' => Carbon::create($tahun, $bulan, 1),
            'jenis_laporan' => 'bulanan',
            'total_kendaraan' => $totalKendaraan,
            'total_pendapatan' => $totalPendapatan,
            'detail_laporan' => [
                'motor' => Kendaraan::whereMonth('waktu_keluar', $bulan)
                    ->whereYear('waktu_keluar', $tahun)
                    ->where('jenis_kendaraan', 'motor')
                    ->count(),
                'mobil' => Kendaraan::whereMonth('waktu_keluar', $bulan)
                    ->whereYear('waktu_keluar', $tahun)
                    ->where('jenis_kendaraan', 'mobil')
                    ->count()
            ]
        ]);
    }
}
