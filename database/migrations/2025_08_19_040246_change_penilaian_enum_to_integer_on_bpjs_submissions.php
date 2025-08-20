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
    public function up(): void
    {
        // Tambah kolom sementara
        Schema::table('bpjs_submissions', function (Blueprint $table) {
            $table->unsignedTinyInteger('penilaian_int')->nullable()->after('penilaian');
        });
        // Konversi ENUM ke angka
        DB::table('bpjs_submissions')->whereNotNull('penilaian')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $nilai = \App\Helpers\PenilaianHelper::labelToNumeric($row->penilaian);
                DB::table('bpjs_submissions')
                    ->where('id', $row->id)
                    ->update(['penilaian_int' => $nilai]);
            }
        });

        // Hapus kolom enum lama
        Schema::table('bpjs_submissions', function (Blueprint $table) {
            $table->dropColumn('penilaian');
        });

        // Rename kolom int jadi penilaian
        Schema::table('bpjs_submissions', function (Blueprint $table) {
            $table->renameColumn('penilaian_int', 'penilaian');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambahkan kembali kolom ENUM
        Schema::table('bpjs_submissions', function (Blueprint $table) {
            $table->enum('penilaian_backup', ['tidak_puas', 'cukup', 'puas', 'sangat_puas'])->nullable()->after('penilaian');
        });

        // Kembalikan nilai angka ke ENUM
        DB::table('bpjs_submissions')->whereNotNull('penilaian')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $label = \App\Helpers\PenilaianHelper::numericToLabel($row->penilaian);
                DB::table('bpjs_submissions')
                    ->where('id', $row->id)
                    ->update(['penilaian_backup' => $label]);
            }
        });

        // Hapus kolom angka
        Schema::table('bpjs_submissions', function (Blueprint $table) {
            $table->dropColumn('penilaian');
        });

        // Rename kolom backup jadi penilaian
        Schema::table('bpjs_submissions', function (Blueprint $table) {
            $table->renameColumn('penilaian_backup', 'penilaian');
        });
    }
};
