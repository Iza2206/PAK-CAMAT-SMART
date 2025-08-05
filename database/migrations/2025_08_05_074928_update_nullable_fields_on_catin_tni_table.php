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
       Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
        $table->string('file_surat_izin_kawin')->nullable()->change();
        $table->string('file_keterangan_belum_menikah_tni')->nullable()->change();
        $table->string('file_pernyataan_kesediaan')->nullable()->change();
        $table->string('file_kta')->nullable()->change();
        $table->string('file_sk_pangkat_terakhir')->nullable()->change();
        $table->string('file_pas_foto_berdampingan_dinas')->nullable()->change();
        $table->string('file_pas_foto_berdampingan_formal')->nullable()->change();

        $table->string('file_ktp')->nullable()->change();
        $table->string('file_kk')->nullable()->change();
        $table->string('file_akta_kelahiran')->nullable()->change();
        $table->string('file_pas_foto_3x4')->nullable()->change();
        $table->string('file_pas_foto_4x6')->nullable()->change();
        $table->string('file_pengantar_rt_rw')->nullable()->change();
        $table->string('file_surat_n1')->nullable()->change();
        $table->string('file_surat_n2')->nullable()->change();
        $table->string('file_surat_n3')->nullable()->change();
        $table->string('file_surat_n4')->nullable()->change();
        $table->string('file_izin_orang_tua')->nullable()->change();
        $table->string('file_status_pernikahan')->nullable()->change();
        $table->string('file_pbb')->nullable()->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
        // optional: kamu bisa kembalikan ke not nullable kalau perlu
    });
    }
};
