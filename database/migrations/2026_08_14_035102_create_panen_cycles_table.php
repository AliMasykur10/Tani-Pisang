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
        Schema::create('panen_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lahan_id')->constrained()->cascadeOnDelete();
            $table->integer('nomor_siklus');
            $table->date('tanggal_panen');
            $table->integer('jumlah_pohon_produktif');
            $table->decimal('total_hasil_kg', 10, 2);
            $table->decimal('harga_per_kg', 10, 2);
            $table->decimal('total_pemasukan', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panen_cycles');
    }
};
