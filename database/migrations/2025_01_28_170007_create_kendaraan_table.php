<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKendaraanTable extends Migration
{
    public function up()
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_plat')->unique();
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

            $table->foreign('slot_parkir_id')
                  ->references('id')
                  ->on('slot_parkir')
                  ->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['nomor_plat', 'status']);
            $table->index('waktu_masuk');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kendaraan');
    }
}
