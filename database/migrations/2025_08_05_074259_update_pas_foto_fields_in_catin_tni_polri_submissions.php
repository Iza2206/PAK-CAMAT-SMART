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
        $table->dropColumn('file_pas_foto_berdampingan');
        $table->string('file_pas_foto_berdampingan_dinas')->nullable();
        $table->string('file_pas_foto_berdampingan_formal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
        $table->dropColumn(['file_pas_foto_berdampingan_dinas', 'file_pas_foto_berdampingan_formal']);
        $table->string('file_pas_foto_berdampingan')->nullable();
        });
    }
};
