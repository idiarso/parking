<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SkenarioSimulasi extends Model
{
    protected $table = 'skenario_simulasi';

    protected $fillable = [
        'simulasi_parkir_id', 
        'nama', 
        'deskripsi', 
        'parameter_kunci', 
        'hasil_simulasi', 
        'status'
    ];

    protected $casts = [
        'parameter_kunci' => 'array',
        'hasil_simulasi' => 'array'
    ];

    // Konstanta Status Skenario
    const STATUS = [
        'draft' => 'Draft',
        'diproses' => 'Sedang Diproses',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan'
    ];

    // Relasi dengan Simulasi Parkir
    public function simulasiParkir()
    {
        return $this->belongsTo(SimulasiParkir::class, 'simulasi_parkir_id');
    }

    // Metode untuk membuat skenario
    public static function buatSkenario($data)
    {
        return self::create([
            'simulasi_parkir_id' => $data['simulasi_id'],
            'nama' => $data['nama'] ?? 'Skenario Baru',
            'deskripsi' => $data['deskripsi'] ?? '',
            'parameter_kunci' => $data['parameter'] ?? [],
            'status' => 'draft'
        ]);
    }

    // Analisis komparatif skenario
    public static function analisisKomparatif($skenarioIds)
    {
        $skenario = self::whereIn('id', $skenarioIds)->get();

        $analisis = [
            'parameter_berbeda' => [],
            'hasil_komparasi' => []
        ];

        // Bandingkan parameter
        $parameterUtama = $skenario->first()->parameter_kunci;
        foreach ($skenario as $item) {
            $perbedaan = array_diff_assoc($item->parameter_kunci, $parameterUtama);
            if (!empty($perbedaan)) {
                $analisis['parameter_berbeda'][] = [
                    'skenario_id' => $item->id,
                    'perbedaan' => $perbedaan
                ];
            }
        }

        // Bandingkan hasil
        $hasilPertama = $skenario->first()->hasil_simulasi;
        foreach ($skenario as $item) {
            $selisih = [];
            foreach ($hasilPertama as $kunci => $nilaiPertama) {
                $nilaiSkenario = $item->hasil_simulasi[$kunci] ?? null;
                if ($nilaiSkenario !== null) {
                    $selisih[$kunci] = [
                        'skenario_id' => $item->id,
                        'nilai_awal' => $nilaiPertama,
                        'nilai_skenario' => $nilaiSkenario,
                        'persentase_perubahan' => round(
                            (($nilaiSkenario - $nilaiPertama) / $nilaiPertama) * 100, 
                            2
                        )
                    ];
                }
            }
            $analisis['hasil_komparasi'][] = $selisih;
        }

        return $analisis;
    }

    // Statistik skenario
    public static function statistikSkenario()
    {
        return [
            'total_skenario' => self::count(),
            'per_status' => self::select('status', DB::raw('COUNT(*) as total'))
                        ->groupBy('status')
                        ->get(),
            'skenario_terakhir' => self::orderBy('created_at', 'desc')
                        ->limit(5)
                        ->with('simulasiParkir')
                        ->get()
        ];
    }
}
