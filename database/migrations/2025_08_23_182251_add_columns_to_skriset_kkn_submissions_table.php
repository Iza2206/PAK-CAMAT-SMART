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
       Schema::table('skriset_kkn_submissions', function (Blueprint $table) {
            $table->unsignedInteger('queue_number')->nullable()->after('id');
            $table->text('saran_kritik')->collation('utf8mb4_unicode_ci')->nullable()->after('queue_number');
            $table->string('file_surat', 191)->collation('utf8mb4_unicode_ci')->nullable()->after('saran_kritik');
            $table->string('surat_final', 191)->collation('utf8mb4_unicode_ci')->nullable()->after('file_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skriset_kkn_submissions', function (Blueprint $table) {
            $table->dropColumn(['queue_number', 'saran_kritik', 'file_surat', 'surat_final']);
        });
    }
};
