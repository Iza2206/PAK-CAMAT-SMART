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
        Schema::table('sppat_gr_submissions', function (Blueprint $table) {
            $table->text('rejected_sekcam_reason')->nullable()->after('approved_sekcam_at');
            $table->text('penilaian')->nullable()->after('rejected_sekcam_reason');
            $table->timestamp('diambil_at')->nullable()->after('penilaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sppat_gr_submissions', function (Blueprint $table) {
            $table->dropColumn(['rejected_sekcam_reason', 'penilaian', 'diambil_at']);
        });
    }
};
