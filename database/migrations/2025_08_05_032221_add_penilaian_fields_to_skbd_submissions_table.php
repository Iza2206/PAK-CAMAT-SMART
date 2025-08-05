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
        Schema::table('skbd_submissions', function (Blueprint $table) {
    $table->enum('penilaian', ['tidak_puas', 'cukup', 'puas', 'sangat_puas'])->nullable()->after('status');
    $table->timestamp('diambil_at')->nullable()->after('penilaian');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skbd_submissions', function (Blueprint $table) {
            //
        });
    }
};
