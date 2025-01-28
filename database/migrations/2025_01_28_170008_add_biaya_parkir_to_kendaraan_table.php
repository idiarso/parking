<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBiayaParkirToKendaraanTable extends Migration
{
    public function up()
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            // Rename kolom
            if (Schema::hasColumn('kendaraan', 'nomor_plat')) {
                $table->renameColumn('nomor_plat', 'plat_nomor');
            }

            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('kendaraan', 'biaya_parkir')) {
                $table->decimal('biaya_parkir', 10, 2)->nullable()->default(0);
            }

            if (!Schema::hasColumn('kendaraan', 'durasi_parkir')) {
                $table->float('durasi_parkir', 8, 2)->nullable()->default(0)->comment('Durasi parkir dalam jam');
            }
        });
    }

    public function down()
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            // Kembalikan nama kolom
            if (Schema::hasColumn('kendaraan', 'plat_nomor')) {
                $table->renameColumn('plat_nomor', 'nomor_plat');
            }

            // Hapus kolom tambahan
            $table->dropColumn(['biaya_parkir', 'durasi_parkir']);
        });
    }
}
