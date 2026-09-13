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
        Schema::table('trouble_reports', function (Blueprint $table) {
            $table->timestamp('selesai_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trouble_reports', function (Blueprint $table) {
            $table->dropColumn('selesai_at');
        });
    }
};
