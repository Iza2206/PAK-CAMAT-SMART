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
        Schema::create('layanan', function (Blueprint $table) {
        $table->id();
        $table->enum('jenis_layanan', [
            'bpjs',
            'bpjs_narkoba',
            'kip',
            'dispensasi_nikah',
            'iumk',
            'sktm_cerai',
            'riset_kkn',
            'skt',
            'sppat_gr',
            'ahli_waris',
            'agunan_bank',
            'silang_sengketa',
            'catin_tni_polri',
            'skbd',
        ]);
        $table->string('pemohon_nama');
        $table->unsignedBigInteger('meja_layanan_user_id');
        $table->enum('status', [
            'draft',
            'dikirim_kasi',
            'dikirim_sekcam',
            'dikirim_camat',
            'disetujui_camat',
            'ditolak'
        ])->default('draft');
        $table->enum('current_role', [
            'meja_layanan',
            'kasi_kesos',
            'sekcam',
            'camat'
        ])->default('meja_layanan');
        $table->text('catatan')->nullable();
        $table->timestamp('verified_at')->nullable();
        $table->timestamps();

        $table->foreign('meja_layanan_user_id')->references('id')->on('users')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
