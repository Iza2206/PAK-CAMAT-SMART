<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkrisetKknSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('skriset_kkn_submissions', function (Blueprint $table) {
            $table->id();

            $table->string('nama_pemohon');
            $table->string('nik_pemohon', 16);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('pendidikan');

            $table->string('file_surat_sekolah');       // surat keterangan sekolah/universitas (path file)
            $table->string('file_izin_pengambilan');    // surat izin pengambilan data dari dinas terkait (path file)

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
        Schema::dropIfExists('skriset_kkn_submissions');
    }
}
