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
        Schema::table('catin_submissions', function (Blueprint $table) {
            // Ubah kolom status jadi varchar(191), default 'diajukan'
            $table->string('status', 191)->default('diajukan')->collation('utf8mb4_unicode_ci')->change();
        });
    }

    public function down()
    {
        Schema::table('catin_submissions', function (Blueprint $table) {
            // Kembalikan ke enum (sesuaikan enum-nya dengan yang awal)
            $table->enum('status', ['diajukan','checked_by_kasi','approved_by_sekcam','approved_by_camat','rejected'])
                ->default('diajukan')
                ->collation('utf8mb4_unicode_ci')
                ->change();
        });
    }
};
