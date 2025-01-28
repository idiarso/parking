<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBiayaParkirToKendaraanTable extends Migration
{
    public function up()
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->decimal('biaya_parkir', 10, 2)->nullable()->default(0);
            $table->integer('durasi_parkir')->nullable()->default(0)->comment('Durasi parkir dalam menit');
        });
    }

    public function down()
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropColumn(['biaya_parkir', 'durasi_parkir']);
        });
    }
}
