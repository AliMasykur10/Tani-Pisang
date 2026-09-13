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
        Schema::create('lahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('luas_panjang_m', 8, 2)->nullable();
            $table->decimal('luas_lebar_m', 8, 2)->nullable();
            $table->decimal('jarak_tanam_m', 5, 2)->nullable();
            $table->decimal('jarak_pagar_m', 5, 2)->nullable();
            $table->integer('estimasi_jumlah_pohon')->nullable();
            $table->string('fase_saat_ini')->default('buka_lahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahans');
    }
};
