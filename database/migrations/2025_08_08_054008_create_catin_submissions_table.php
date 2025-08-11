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
        Schema::create('catin_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('queue_number');
            $table->string('nama_pemohon');
            $table->string('nik_pemohon');
            $table->string('jenis_kelamin');
            $table->string('pendidikan');

            $table->string('file_na_pria');
            $table->string('file_na_wanita');
            $table->string('file_kk_pria');
            $table->string('file_kk_wanita');
            $table->string('file_ktp_pria');
            $table->string('file_ktp_wanita');
            $table->string('file_akte_cerai_pria')->nullable();
            $table->string('file_akte_cerai_wanita')->nullable();
            $table->string('file_pbb');

            $table->enum('status', ['diajukan', 'checked_by_kasi', 'approved_by_sekcam', 'approved_by_camat', 'rejected'])->default('diajukan');

            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_sekcam_at')->nullable();
            $table->timestamp('approved_camat_at')->nullable();

            $table->text('rejected_reason')->nullable();
            $table->text('rejected_sekcam_reason')->nullable();
            $table->text('rejected_camat_reason')->nullable();

            $table->text('penilaian')->nullable();
            $table->timestamp('diambil_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catin_submissions');
    }
};
