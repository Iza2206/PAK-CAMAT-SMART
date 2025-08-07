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
            $table->text('rejected_camat_reason')->nullable()->after('approved_camat_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sppat_gr_submissions', function (Blueprint $table) {
            $table->dropColumn('rejected_camat_reason');
        });
    }
};
