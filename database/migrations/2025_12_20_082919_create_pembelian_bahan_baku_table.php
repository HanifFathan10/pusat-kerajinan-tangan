<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pembelian_bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_beli');
            $table->string('supplier')->nullable();
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('detail_pembelian_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_bahan_baku_id')->constrained('pembelian_bahan_baku')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->cascadeOnDelete();
            $table->integer('jumlah_beli');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('sub_total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahan_baku');
    }
};
