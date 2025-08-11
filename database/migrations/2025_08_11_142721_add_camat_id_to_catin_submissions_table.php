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
            $table->unsignedBigInteger('camat_id')->nullable()->after('approved_camat_at');
            $table->foreign('camat_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('catin_submissions', function (Blueprint $table) {
            $table->dropForeign(['camat_id']);
            $table->dropColumn('camat_id');
        });
    }
};
