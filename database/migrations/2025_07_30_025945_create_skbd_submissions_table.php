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
            Schema::create('skbd_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama_pemohon');
            $table->char('nik_pemohon', 16);
            $table->string('skbd_desa');
            $table->string('ktp');
            $table->string('kk');
            $table->string('tanda_lunas_pbb');
            $table->string('status')->default('diajukan');
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
        Schema::dropIfExists('skbd_submissions');
    }
};
