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
        Schema::create('sengketa_submissions', function (Blueprint $table) {
        $table->id();
        $table->integer('queue_number')->nullable();

        $table->string('nama_pemohon');
        $table->string('jenis_kelamin')->nullable();
        $table->string('pendidikan')->nullable();
        $table->char('nik_pemohon', 16);

        // Dokumen
        $table->string('surat_silang_sengketa'); // dari desa
        $table->string('foto_copy_surat_tanah');
        $table->string('bukti_lunas_pbb');

        // Status tracking
        $table->string('status', 100)->default('diajukan');
        $table->timestamp('verified_at')->nullable(); // Kasi Pemerintahan
        $table->timestamp('approved_sekcam_at')->nullable();
        $table->timestamp('approved_camat_at')->nullable();

        // Alasan penolakan
        $table->string('rejected_reason')->nullable(); // Kasi Pemerintahan
        $table->string('rejected_sekcam_reason')->nullable();
        $table->string('rejected_camat_reason')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sengketa_submissions');
    }
};
