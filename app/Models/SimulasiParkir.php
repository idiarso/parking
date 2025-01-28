<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimulasiParkir extends Model
{
    protected $table = 'simulasi_parkir';

    protected $fillable = [
        'tanggal_simulasi', 
        'jam_mulai', 
        'jam_selesai', 
        'total_slot', 
        'kapasitas_motor', 
        'kapasitas_mobil',
        'prediksi_kendaraan_masuk',
        'prediksi_kendaraan_keluar',
        'prediksi_pendapatan',
        'status_simulasi',
        'catatan'
    ];

    protected $casts = [
        'tanggal_simulasi' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'prediksi_kendaraan_masuk' => 'integer',
        'prediksi_kendaraan_keluar' => 'integer',
        'prediksi_pendapatan' => 'float'
    ];

    // Konstanta untuk kategori simulasi
    const STATUS = [
        'draft' => 'Draft',
        'diproses' => 'Sedang Diproses',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan'
    ];

    // Relasi dengan Skenario Simulasi
    public function skenario()
    {
        return $this->hasMany(SkenarioSimulasi::class, 'simulasi_parkir_id');
    }

    // Metode untuk membuat simulasi baru
    public static function buatSimulasi($data)
    {
        return self::create([
            'tanggal_simulasi' => $data['tanggal'] ?? now(),
            'jam_mulai' => $data['jam_mulai'] ?? now(),
            'jam_selesai' => $data['jam_selesai'] ?? now()->addHours(12),
            'total_slot' => $data['total_slot'] ?? 100,
            'kapasitas_motor' => $data['kapasitas_motor'] ?? 60,
            'kapasitas_mobil' => $data['kapasitas_mobil'] ?? 40,
            'status_simulasi' => 'draft'
        ]);
    }

    // Metode untuk menjalankan simulasi
    public function jalankanSimulasi()
    {
        // Simulasi berdasarkan data historis
        $dataHistoris = $this->ambilDataHistoris();
        
        $prediksiMasuk = $this->hitungPrediksiKendaraan($dataHistoris, 'masuk');
        $prediksiKeluar = $this->hitungPrediksiKendaraan($dataHistoris, 'keluar');
        
        $this->update([
            'prediksi_kendaraan_masuk' => $prediksiMasuk,
            'prediksi_kendaraan_keluar' => $prediksiKeluar,
            'prediksi_pendapatan' => $this->hitungPrediksiPendapatan($prediksiMasuk),
            'status_simulasi' => 'selesai'
        ]);

        return $this;
    }

    // Ambil data historis untuk simulasi
    private function ambilDataHistoris()
    {
        // Ambil data parkir 30 hari terakhir
        return Pembayaran::select(
            DB::raw('HOUR(waktu_masuk) as jam'),
            DB::raw('COUNT(*) as total_kendaraan'),
            DB::raw('SUM(total_bayar) as total_pendapatan')
        )
        ->whereBetween('waktu_masuk', [now()->subDays(30), now()])
        ->groupBy('jam')
        ->orderBy('jam')
        ->get();
    }

    // Hitung prediksi kendaraan
    private function hitungPrediksiKendaraan($dataHistoris, $tipe)
    {
        // Algoritma prediksi sederhana berdasarkan data historis
        $totalKendaraan = $dataHistoris->sum('total_kendaraan');
        $jamPuncak = $dataHistoris->max('total_kendaraan');
        
        // Faktor koreksi berdasarkan jam puncak
        $faktorKoreksi = $tipe === 'masuk' ? 1.2 : 0.8;
        
        return round($totalKendaraan * $faktorKoreksi / 30);
    }

    // Hitung prediksi pendapatan
    private function hitungPrediksiPendapatan($prediksiMasuk)
    {
        // Asumsi tarif rata-rata per kendaraan
        $tarifRataMotor = 3000;
        $tarifRataMobil = 5000;

        // Proporsi kendaraan
        $persenMotor = $this->kapasitas_motor / $this->total_slot;
        $persenMobil = $this->kapasitas_mobil / $this->total_slot;

        $pendapatanMotor = $prediksiMasuk * $persenMotor * $tarifRataMotor;
        $pendapatanMobil = $prediksiMasuk * $persenMobil * $tarifRataMobil;

        return $pendapatanMotor + $pendapatanMobil;
    }

    // Analisis skenario
    public function analisisSkenario($parameter)
    {
        $skenario = $this->skenario()->create([
            'nama' => $parameter['nama'] ?? 'Skenario Baru',
            'deskripsi' => $parameter['deskripsi'] ?? '',
            'parameter_kunci' => json_encode($parameter)
        ]);

        // Jalankan simulasi dengan parameter khusus
        $hasilSimulasi = $this->simulasiKhusus($parameter);

        $skenario->update([
            'hasil_simulasi' => json_encode($hasilSimulasi),
            'status' => 'selesai'
        ]);

        return $skenario;
    }

    // Simulasi dengan parameter khusus
    private function simulasiKhusus($parameter)
    {
        // Contoh parameter: perubahan tarif, kapasitas, dll
        $faktorPenyesuaian = $parameter['faktor_penyesuaian'] ?? 1;
        
        return [
            'prediksi_kendaraan_masuk' => $this->prediksi_kendaraan_masuk * $faktorPenyesuaian,
            'prediksi_pendapatan' => $this->prediksi_pendapatan * $faktorPenyesuaian,
            'catatan' => 'Simulasi dengan faktor penyesuaian: ' . $faktorPenyesuaian
        ];
    }

    // Statistik simulasi
    public static function statistikSimulasi()
    {
        return [
            'total_simulasi' => self::count(),
            'per_status' => self::select('status_simulasi', DB::raw('COUNT(*) as total'))
                        ->groupBy('status_simulasi')
                        ->get(),
            'simulasi_terakhir' => self::orderBy('created_at', 'desc')->limit(5)->get()
        ];
    }
}
