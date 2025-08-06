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
       Schema::table('ahliwaris_submissions', function (Blueprint $table) {
        $table->string('rejected_sekcam_reason')->nullable()->after('rejected_reason');
        $table->string('rejected_camat_reason')->nullable()->after('rejected_sekcam_reason');
        $table->text('penilaian')->nullable()->after('rejected_camat_reason');
        $table->timestamp('diambil_at')->nullable()->after('penilaian');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ahliwaris_submissions', function (Blueprint $table) {
        $table->dropColumn([
            'rejected_sekcam_reason',
            'rejected_camat_reason',
            'penilaian',
            'diambil_at'
        ]);
    });
    }
};
