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
       Schema::table('sppat_gr_submissions', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan
            $table->dropColumn([
                'file_pernyataan_ahli_waris',
                'file_akta_kematian',
                'file_ktp_ahli_waris',
                'file_kk_ahli_waris',
            ]);

            // Tambahkan kolom baru sesuai kebutuhan
            $table->string('file_formulir')->after('file_permohonan');
            $table->string('file_alas_hak_tanah')->after('file_formulir');
            $table->string('file_ktp')->after('file_alas_hak_tanah');
            $table->string('file_kk')->after('file_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sppat_gr_submissions', function (Blueprint $table) {
            // Tambahkan kembali kolom lama jika perlu rollback
            $table->string('file_pernyataan_ahli_waris')->nullable();
            $table->string('file_akta_kematian')->nullable();
            $table->string('file_ktp_ahli_waris')->nullable();
            $table->string('file_kk_ahli_waris')->nullable();

            // Hapus kolom baru
            $table->dropColumn([
                'file_formulir',
                'file_alas_hak_tanah',
                'file_ktp',
                'file_kk',
            ]);
        });
    }
};
