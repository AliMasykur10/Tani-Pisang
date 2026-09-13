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
        Schema::create('partner_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lahan_id')->constrained()->cascadeOnDelete();
            $table->enum('skema', ['sewa', 'bagi_hasil']);
            $table->decimal('nominal_sewa', 12, 2)->nullable();
            $table->decimal('persentase_bagi_hasil', 5, 2)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_agreements');
    }
};
