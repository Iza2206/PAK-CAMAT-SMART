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
            $table->string('status', 191)
                  ->collation('utf8mb4_unicode_ci')
                  ->default('diajukan')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skriset_kkn_submissions', function (Blueprint $table) {
            $table->enum('status', [
                'diajukan',
                'checked_by_kasi',
                'approved_by_sekcam',
                'approved_by_camat',
                'rejected'
            ])->collation('utf8mb4_unicode_ci')
              ->default('diajukan')
              ->change();
        });
    }
};
