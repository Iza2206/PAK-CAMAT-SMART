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
        Schema::table('catin_submissions', function (Blueprint $table) {
            $table->string('file_surat')->nullable()->after('file_pbb'); 
            // 'file_pbb' ganti sesuai kolom terakhir sebelum ini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catin_submissions', function (Blueprint $table) {
            $table->dropColumn('file_surat');
        });
    }
};
