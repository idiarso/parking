<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanTable extends Migration
{
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            
            // Informasi umum laporan
            $table->enum('jenis_laporan', [
                'harian', 
                'mingguan', 
                'bulanan', 
                'tahunan', 
                'khusus'
            ])->default('harian');
            
            // Periode laporan
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // Statistik kendaraan
            $table->integer('total_kendaraan')->default(0);
            $table->integer('kendaraan_motor')->default(0);
            $table->integer('kendaraan_mobil')->default(0);
            
            // Statistik pendapatan
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('pendapatan_motor', 15, 2)->default(0);
            $table->decimal('pendapatan_mobil', 15, 2)->default(0);
            
            // Statistik slot parkir
            $table->integer('total_slot')->default(0);
            $table->integer('slot_terisi')->default(0);
            $table->integer('slot_tersedia')->default(0);
            
            // Informasi tambahan
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            
            // Foreign key untuk user yang membuat laporan
            $table->foreign('generated_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Status laporan
            $table->enum('status', [
                'draft', 
                'final', 
                'terverifikasi', 
                'dibatalkan'
            ])->default('draft');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk pencarian dan sorting
            $table->index(['jenis_laporan', 'tanggal_mulai', 'tanggal_selesai']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan');
    }
}
