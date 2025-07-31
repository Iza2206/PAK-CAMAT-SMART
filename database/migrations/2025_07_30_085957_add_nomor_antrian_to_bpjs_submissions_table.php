<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('bpjs_submissions', function (Blueprint $table) {
        $table->integer('nomor_antrian')->nullable();
        $table->date('submitted_date')->nullable(); // Untuk tracking harian
    });
}

public function down()
{
    Schema::table('bpjs_submissions', function (Blueprint $table) {
        $table->dropColumn(['nomor_antrian', 'submitted_date']);
    });
}

};
