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
        Schema::create('trouble_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lahan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('urgensi', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['dilaporkan', 'ditindaklanjuti', 'selesai'])->default('dilaporkan');
            $table->json('foto_urls')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trouble_reports');
    }
};
