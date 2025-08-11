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
            $table->string('surat_final')->nullable()->after('file_surat')->comment('File surat final hasil tanda tangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('catin_submissions', function (Blueprint $table) {
            $table->dropColumn('surat_final');
        });
    }
};
