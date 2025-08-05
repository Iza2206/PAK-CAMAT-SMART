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
        $table->string('penilaian')->nullable();
        $table->timestamp('diambil_at')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catin_tni_polri_submissions', function (Blueprint $table) {
        $table->dropColumn(['penilaian', 'diambil_at']);
    });
    }
};
