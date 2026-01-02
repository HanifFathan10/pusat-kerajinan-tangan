<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_produksi', function (Blueprint $table) {
            // Kita tambahkan kolom-kolom QC & Keuangan sekalian agar lengkap
            if (!Schema::hasColumn('jadwal_produksi', 'biaya_tenaga_kerja')) {
                $table->decimal('biaya_tenaga_kerja', 15, 2)->default(0)->after('status_produksi');
            }

            if (!Schema::hasColumn('jadwal_produksi', 'hasil_produksi')) {
                $table->integer('hasil_produksi')->default(0)->after('biaya_tenaga_kerja');
            }

            if (!Schema::hasColumn('jadwal_produksi', 'jumlah_reject')) {
                $table->integer('jumlah_reject')->default(0)->after('hasil_produksi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_produksi', function (Blueprint $table) {
            $table->dropColumn(['biaya_tenaga_kerja', 'hasil_produksi', 'jumlah_reject']);
        });
    }
};
