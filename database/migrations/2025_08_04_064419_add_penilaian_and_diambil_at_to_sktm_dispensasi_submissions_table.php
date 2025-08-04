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
       Schema::table('sktm_dispensasi_submissions', function (Blueprint $table) {
        $table->text('penilaian')->nullable()->after('rejected_reason'); // atau sesuaikan posisi
        $table->timestamp('diambil_at')->nullable()->after('penilaian');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sktm_dispensasi_submissions', function (Blueprint $table) {
        $table->dropColumn(['penilaian', 'diambil_at']);
    });
    }
};
