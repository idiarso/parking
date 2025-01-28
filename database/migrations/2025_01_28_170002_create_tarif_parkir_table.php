<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifParkirTable extends Migration
{
    public function up()
    {
        Schema::create('tarif_parkir', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kendaraan', ['motor', 'mobil']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('tarif_per_jam', 10, 2);
            $table->decimal('tarif_per_hari', 10, 2);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tarif_parkir');
    }
}
