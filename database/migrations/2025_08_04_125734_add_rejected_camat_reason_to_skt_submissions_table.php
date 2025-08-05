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
    Schema::table('skt_submissions', function (Blueprint $table) {
        $table->text('rejected_camat_reason')->nullable()->after('rejected_sekcam_reason');
    });
}

public function down()
{
    Schema::table('skt_submissions', function (Blueprint $table) {
        $table->dropColumn('rejected_camat_reason');
    });
}

};
