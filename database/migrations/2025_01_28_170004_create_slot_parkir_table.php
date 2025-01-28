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
            $table->string('kode_slot')->unique();
            $table->enum('jenis_kendaraan', ['motor', 'mobil']);
            $table->enum('status', ['tersedia', 'terisi', 'tidak_tersedia'])->default('tersedia');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('slot_parkir');
    }
}
