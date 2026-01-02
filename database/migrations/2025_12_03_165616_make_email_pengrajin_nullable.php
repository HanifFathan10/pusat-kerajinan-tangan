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
        Schema::table('pengrajin', function (Blueprint $table) {
            // Ubah kolom email agar BOLEH KOSONG (NULL)
            $table->string('email_pengrajin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengrajin', function (Blueprint $table) {
            //
        });
    }
};
