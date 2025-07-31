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
        // Hapus kolom enum lama
        Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Tambahkan ulang kolom status sebagai string biasa
        Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
            $table->string('status', 191)->collation('utf8mb4_unicode_ci')->default('diajukan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       // Rollback ke enum jika diperlukan
        Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
            $table->enum('status', ['diajukan', 'diproses_kasi', 'diproses_sekcam', 'disetujui_camat', 'ditolak'])->default('diajukan');
        });
    }
};
