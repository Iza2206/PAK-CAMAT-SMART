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
        Schema::table('sktm_dispensasi_submissions', function (Blueprint $table) {
            $table->text('saran_kritik')->nullable()->after('penilaian');
        });
    }

    public function down()
    {
        Schema::table('sktm_dispensasi_submissions', function (Blueprint $table) {
            $table->dropColumn('saran_kritik');
        });
    }
};
