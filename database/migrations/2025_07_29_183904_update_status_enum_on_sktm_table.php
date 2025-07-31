<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // MySQL doesn't allow altering enum directly, so use raw SQL
         DB::statement("ALTER TABLE sktm_dispensasi_submissions 
        MODIFY COLUMN status VARCHAR(100) NOT NULL DEFAULT 'diajukan'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE sktm_dispensasi_submissions 
            MODIFY COLUMN status ENUM(
                'diajukan','checked_by_kasi','approved_by_sekcam','approved_by_camat',
                'rejected_by_kasi','rejected_by_sekcam','rejected_by_camat'
            ) NOT NULL DEFAULT 'diajukan'");
    }
};
