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
       Schema::create('agunan_submissions', function (Blueprint $table) {
        $table->id();
        $table->string('nama_pemohon');
        $table->string('nik');
        $table->string('no_kk');
        $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
        $table->string('pendidikan');

        $table->string('file_surat_tanah_asli');
        $table->string('file_ktp');
        $table->string('file_pengantar_desa');
        $table->string('file_surat_tidak_sengketa');
        $table->string('file_pbb');

        $table->string('status')->default('diajukan');
        $table->timestamp('verified_at')->nullable();
        $table->timestamp('approved_sekcam_at')->nullable();
        $table->timestamp('approved_camat_at')->nullable();
        $table->string('rejected_sekcam_reason')->nullable();
        $table->string('rejected_camat_reason')->nullable();
        $table->unsignedInteger('queue_number')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agunan_submissions');
    }
};
