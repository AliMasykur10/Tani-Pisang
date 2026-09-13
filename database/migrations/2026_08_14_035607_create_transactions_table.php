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
         Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lahan_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('panen_cycle_id')->nullable()->constrained('panen_cycles')->nullOnDelete();
        $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
        $table->enum('jenis', ['pengeluaran', 'pemasukan']);
        $table->string('kategori');
        $table->decimal('jumlah', 12, 2);
        $table->boolean('is_cash')->default(true);
        $table->date('tanggal');
        $table->text('keterangan')->nullable();
        $table->string('foto_bukti')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
