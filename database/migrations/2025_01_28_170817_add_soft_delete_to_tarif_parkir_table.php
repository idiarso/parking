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
            // Tambahkan kolom soft delete hanya jika belum ada
            if (!Schema::hasColumn('tarif_parkir', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarif_parkir', function (Blueprint $table) {
            // Hapus kolom soft delete hanya jika ada
            if (Schema::hasColumn('tarif_parkir', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
