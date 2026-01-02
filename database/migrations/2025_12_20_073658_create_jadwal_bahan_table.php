<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_bahan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jadwal_produksi_id')
                ->constrained('jadwal_produksi')
                ->cascadeOnDelete();

            $table->foreignId('bahan_baku_id')
                ->constrained('bahan_baku')
                ->cascadeOnDelete();

            $table->integer('jumlah_dipakai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_bahan');
    }
};
