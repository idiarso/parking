<?php

namespace App\Models\Parkir;

use Illuminate\Database\Eloquent\Model;

class SlotParkir extends Model
{
    protected $table = 'slot_parkir';

    protected $fillable = [
        'nomor_slot',
        'jenis_kendaraan',
        'status',
        'kendaraan_id'
    ];

    // Relasi dengan kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    // Scope untuk slot kosong
    public function scopeKosong($query)
    {
        return $query->where('status', 'kosong');
    }

    // Scope untuk slot terisi
    public function scopeTerisi($query)
    {
        return $query->where('status', 'terisi');
    }

    // Metode untuk mengatur status slot
    public function aturStatus($status, $kendaraanId = null)
    {
        $this->status = $status;
        $this->kendaraan_id = $kendaraanId;
        $this->save();

        return $this;
    }

    // Metode untuk mencari slot kosong berdasarkan jenis kendaraan
    public static function cariSlotKosong($jenisKendaraan)
    {
        return self::where('jenis_kendaraan', $jenisKendaraan)
                   ->where('status', 'kosong')
                   ->first();
    }
}
