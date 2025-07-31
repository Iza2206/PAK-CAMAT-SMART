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
           $table->string('jenis_kelamin')->nullable()->after('nama_pemohon');
            $table->string('pendidikan')->nullable()->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skbd_submissions', function (Blueprint $table) {
             $table->dropColumn(['jenis_kelamin', 'pendidikan']);
        });
    }
};
