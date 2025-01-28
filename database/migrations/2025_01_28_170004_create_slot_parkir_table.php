<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlotParkirTable extends Migration
{
    public function up()
    {
        Schema::create('slot_parkir', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->enum('jenis_kendaraan', ['motor', 'mobil']);
            $table->enum('status', ['kosong', 'terisi', 'rusak', 'maintenance'])->default('kosong');
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('slot_parkir');
    }
}
