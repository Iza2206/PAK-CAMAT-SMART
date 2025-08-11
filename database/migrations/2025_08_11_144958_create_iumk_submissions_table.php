<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIumkSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('iumk_submissions', function (Blueprint $table) {
            $table->id();

            $table->string('nama_pemohon');
            $table->string('nik_pemohon');
            $table->string('alamat_usaha')->nullable();

            $table->string('surat_keterangan_usaha');   // Surat Keterangan Usaha dari Desa
            $table->string('foto_tempat_usaha');         // Foto/gambar tempat usaha
            $table->string('file_kk');                    // Fotocopy KK
            $table->string('file_ktp');                   // Fotocopy KTP
            $table->string('pasphoto_3x4_1');             // Pasphoto 3x4 warna 1
            $table->string('pasphoto_3x4_2');             // Pasphoto 3x4 warna 2
            $table->string('file_pbb');                    // Tanda Lunas PBB
            $table->string('file_surat')->nullable();                   
            $table->string('surat_final')->nullable();                   

            $table->enum('status', ['diajukan', 'checked_by_kasi', 'approved_by_sekcam', 'approved_by_camat', 'rejected'])->default('diajukan');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_sekcam_at')->nullable();
            $table->timestamp('approved_camat_at')->nullable();

            $table->text('rejected_reason')->nullable();
            $table->text('rejected_sekcam_reason')->nullable();
            $table->text('rejected_camat_reason')->nullable();

            $table->text('penilaian')->nullable();
            $table->timestamp('diambil_at')->nullable();

            $table->unsignedBigInteger('camat_id')->nullable();
            $table->foreign('camat_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('iumk_submissions');
    }
}
