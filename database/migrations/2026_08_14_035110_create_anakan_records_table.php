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
        Schema::create('anakan_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panen_cycle_id')->constrained()->cascadeOnDelete();
            $table->integer('jumlah_muncul');
            $table->integer('jumlah_disisakan');
            $table->integer('jumlah_dijual')->default(0);
            $table->integer('jumlah_dipindah_lahan_lain')->default(0);
            $table->integer('jumlah_dibuang')->default(0);
            $table->decimal('nilai_estimasi_per_batang', 10, 2)->nullable();
            $table->foreignId('lahan_tujuan_id')->nullable()->constrained('lahans')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anakan_records');
    }
};
