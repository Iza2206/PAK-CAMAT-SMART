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
        Schema::create('catin_tni_polri_submissions', function (Blueprint $table) {
            $table->id();

            // Sistem antrian dan user
            $table->unsignedInteger('queue_number')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Data pemohon
            $table->string('nama_pemohon');
            $table->char('nik_pemohon', 16);
            $table->string('jenis_kelamin')->nullable();
            $table->string('pendidikan')->nullable();

            // Dokumen dari pihak calon pengantin sipil
            $table->string('file_ktp_kk');                      // Fotokopi KTP dan KK
            $table->string('file_akta_kelahiran');              // Akta Kelahiran
            $table->string('file_pas_foto_3x4');                // Pas Foto 3x4
            $table->string('file_pas_foto_4x6');                // Pas Foto 4x6
            $table->string('file_pengantar_rt_rw');             // Surat Pengantar RT/RW
            $table->string('file_surat_n1');                    // Surat N1 - Keterangan untuk Nikah
            $table->string('file_surat_n2');                    // Surat N2 - Asal Usul
            $table->string('file_surat_n3');                    // Surat N3 - Persetujuan Mempelai
            $table->string('file_surat_n4');                    // Surat N4 - Tentang Orang Tua
            $table->string('file_izin_orang_tua')->nullable();  // Jika umur <21 tahun
            $table->string('file_status_pernikahan');           // Surat Belum Pernah Menikah / Akta Cerai

            // Dokumen dari pihak calon pengantin TNI/Polri
            $table->string('file_surat_izin_kawin');                // Surat Izin Kawin dari Kesatuan
            $table->string('file_keterangan_belum_menikah_tni');   // Surat belum menikah dari kesatuan
            $table->string('file_kta');                             // Kartu Tanda Anggota
            $table->string('file_sk_pangkat_terakhir');            // SK Pangkat Terakhir
            $table->string('file_pernyataan_kesediaan');           // Surat Pernyataan belum menikah & mendampingi
            $table->string('file_pas_foto_berdampingan');          // Pas foto berdampingan formal & dinas

            // Tambahan dari Kecamatan / KUA
            $table->string('file_pbb');                             // Bukti Lunas PBB

            // Alur status dan verifikasi
            $table->enum('status', ['diajukan', 'diproses_kasi', 'diproses_sekcam', 'disetujui_camat', 'ditolak'])->default('diajukan');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_sekcam_at')->nullable();
            $table->timestamp('approved_camat_at')->nullable();
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
        Schema::dropIfExists('catin_tni_polri_submissions');
    }
};
