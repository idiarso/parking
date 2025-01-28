<?php

namespace App\Models\Parkir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Parkir\Kendaraan;

class SlotParkir extends Model
{
    use SoftDeletes;

    protected $table = 'slot_parkir';

    protected $fillable = [
        'nomor', 
        'jenis_kendaraan', 
        'status', 
        'lokasi', 
        'keterangan'
    ];

    protected $casts = [
        'aktif' => 'boolean'
    ];

    // Relasi dengan kendaraan
    public function kendaraan()
    {
        return $this->hasOne(Kendaraan::class, 'slot_parkir_id');
    }

    // Scope untuk slot tersedia
    public function scopeTersedia($query)
    {
        return $query->where('status', 'kosong');
    }

    // Scope untuk slot berdasarkan jenis kendaraan
    public function scopeJenisKendaraan($query, $jenis)
    {
        return $query->where('jenis_kendaraan', $jenis);
    }

    // Metode untuk mengubah status slot
    public function ubahStatus($status)
    {
        $statusValid = ['kosong', 'terisi', 'rusak', 'maintenance'];
        
        if (!in_array($status, $statusValid)) {
            throw new \InvalidArgumentException('Status slot tidak valid');
        }

        $this->status = $status;
        $this->save();

        return $this;
    }

    // Metode untuk mencari slot kosong berdasarkan jenis kendaraan
    public static function cariSlotKosong($jenisKendaraan)
    {
        return self::tersedia()
            ->jenisKendaraan($jenisKendaraan)
            ->first();
    }

    // Metode untuk menghitung slot tersedia
    public static function hitungSlotTersedia($jenisKendaraan = null)
    {
        $query = self::tersedia();
        
        if ($jenisKendaraan) {
            $query->jenisKendaraan($jenisKendaraan);
        }

        return $query->count();
    }
}
