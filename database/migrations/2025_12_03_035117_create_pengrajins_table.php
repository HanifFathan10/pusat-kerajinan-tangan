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
        Schema::create('pengrajin', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengrajin');
            $table->string('email_pengrajin')->unique();
            $table->string('telepon_pengrajin');
            $table->string('alamat_pengrajin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengrajin');
    }
};
