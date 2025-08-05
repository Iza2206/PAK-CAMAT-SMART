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
        $table->dropColumn('file_ktp_kk');
        $table->string('file_ktp')->nullable();
        $table->string('file_kk')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
        $table->dropColumn(['file_ktp', 'file_kk']);
        $table->string('file_ktp_kk')->nullable();
    });
    }
};
