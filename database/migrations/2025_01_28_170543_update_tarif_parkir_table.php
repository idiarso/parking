<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tarif_parkir', function (Blueprint $table) {
            // Tambah kolom denda per jam hanya jika belum ada
            if (!Schema::hasColumn('tarif_parkir', 'denda_per_jam')) {
                $table->integer('denda_per_jam')->nullable()->default(0);
            }
            
            // Tambah kolom keterangan hanya jika belum ada
            if (!Schema::hasColumn('tarif_parkir', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
            
            // Hapus kolom jam_mulai dan jam_selesai hanya jika ada
            if (Schema::hasColumn('tarif_parkir', 'jam_mulai')) {
                $table->dropColumn(['jam_mulai', 'jam_selesai']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarif_parkir', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            if (!Schema::hasColumn('tarif_parkir', 'jam_mulai')) {
                $table->time('jam_mulai')->nullable();
                $table->time('jam_selesai')->nullable();
            }
            
            // Hapus kolom yang ditambahkan
            if (Schema::hasColumn('tarif_parkir', 'denda_per_jam')) {
                $table->dropColumn(['denda_per_jam', 'keterangan']);
            }
        });
    }
};
