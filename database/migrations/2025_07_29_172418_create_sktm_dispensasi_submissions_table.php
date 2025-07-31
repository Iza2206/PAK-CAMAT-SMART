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
        Schema::create('sktm_dispensasi_submissions', function (Blueprint $table) {
    $table->id();
    $table->string('nama_pemohon');
    $table->char('nik_pemohon', 16);
    
    $table->string('sktm_desa'); // Surat dari desa diketahui camat
    $table->string('kk');
    $table->string('ktp');
    $table->string('buku_nikah');
    $table->string('tanda_lunas_pbb');

    // Alur status
    $table->enum('status', ['diajukan', 'checked_by_kasi', 'approved_by_sekcam', 'approved_by_camat', 'rejected'])->default('diajukan');

    // Timestamp proses
    $table->timestamp('verified_at')->nullable();
    $table->timestamp('approved_sekcam_at')->nullable();
    $table->timestamp('approved_camat_at')->nullable();

    // Alasan penolakan
    $table->string('rejected_reason')->nullable();
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
        Schema::dropIfExists('sktm_dispensasi_submissions');
    }
};
