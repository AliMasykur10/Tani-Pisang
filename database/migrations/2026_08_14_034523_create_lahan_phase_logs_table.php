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
        Schema::create('lahan_phase_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lahan_id')->constrained()->cascadeOnDelete();
            $table->string('fase');
            $table->timestamp('tanggal_mulai');
            $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahan_phase_logs');
    }
};
