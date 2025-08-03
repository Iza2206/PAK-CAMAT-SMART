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
        Schema::create('skt_submissions', function (Blueprint $table) {
    $table->id();
    $table->string('nama_pemohon');
    $table->string('nik_pemohon');
    $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
    $table->string('pendidikan')->nullable();

    $table->string('file_permohonan');
    $table->string('file_alas_hak');
    $table->string('file_kk');
    $table->string('file_ktp');
    $table->string('file_pbb');

    $table->string('status')->default('diajukan');
    $table->timestamp('verified_at')->nullable();
    $table->timestamp('approved_sekcam_at')->nullable();
    $table->timestamp('approved_camat_at')->nullable();
    $table->text('rejected_reason')->nullable();

    $table->string('queue_number')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skt_submissions');
    }
};
