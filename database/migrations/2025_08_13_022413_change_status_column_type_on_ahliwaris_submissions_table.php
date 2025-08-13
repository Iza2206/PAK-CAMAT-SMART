<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ahliwaris_submissions', function (Blueprint $table) {
            // Ubah kolom status dari enum ke varchar(191)
            $table->string('status', 191)->default('diajukan')->change();
        });
    }

    public function down()
    {
        Schema::table('ahliwaris_submissions', function (Blueprint $table) {
            // Kembalikan kolom status menjadi enum seperti semula
            $table->enum('status', ['diajukan','verifikasi_kasi','verifikasi_sekcam','verifikasi_camat','ditolak'])
                  ->default('diajukan')
                  ->collation('utf8mb4_unicode_ci')
                  ->change();
        });
    }
};
