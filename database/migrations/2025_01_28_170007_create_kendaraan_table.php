<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKendaraanTable extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('plat_nomor')->unique();
            $table->enum('jenis_kendaraan', ['motor', 'mobil']);
            $table->unsignedBigInteger('slot_parkir_id')->nullable();
            $table->timestamp('waktu_masuk')->nullable();
            $table->timestamp('waktu_keluar')->nullable();
            $table->enum('status', [
                'parkir', 
                'keluar', 
                'pending', 
                'hilang'
            ])->default('parkir');
            $table->string('pemilik')->nullable();
            $table->string('merk')->nullable();
            $table->string('warna')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('durasi_parkir', 10, 2)->nullable();
            $table->decimal('biaya_parkir', 15, 2)->nullable();

            // Tambahkan soft deletes hanya jika belum ada
            if (!Schema::hasColumn('kendaraan', 'deleted_at')) {
                $table->softDeletes();
            }
            
            $table->timestamps();
            
            $table->foreign('slot_parkir_id')
                  ->references('id')
                  ->on('slot_parkir')
                  ->onDelete('set null');

            $table->index(['plat_nomor', 'status']);
            $table->index('waktu_masuk');
        });
    }

    public function down(): void
    {
        // Hapus kolom soft delete hanya jika ada
        if (Schema::hasColumn('kendaraan', 'deleted_at')) {
            Schema::table('kendaraan', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
        
        Schema::dropIfExists('kendaraan');
    }
}
