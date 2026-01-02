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
        Schema::create('tim_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            $table->string('email_keuangan')->unique();
            $table->string('nik_karyawan')->unique()->nullable();
            $table->string('jabatan')->default('Staff');
            $table->string('telepon')->nullable();
            $table->string('foto_profil')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tim_keuangan');
    }
};
