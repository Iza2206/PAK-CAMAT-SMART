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
       Schema::create('bpjs_submissions', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // ✅ Di sini tempat yang benar

            $table->id();
            $table->string('nama_pemohon');
            $table->char('nik_pemohon', 16);
            $table->string('surat_permohonan');
            $table->string('sktm');
            $table->string('kk');
            $table->string('ktp');
            $table->string('tanda_lunas_pbb');
            $table->string('status')->default('diajukan');

            // Tracking waktu proses
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_sekcam_at')->nullable();
            $table->timestamp('approved_camat_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpjs_submissions');
    }
};
