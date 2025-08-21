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
         Schema::table('iumk_submissions', function (Blueprint $table) {
            // Tambah kolom baru
            $table->unsignedInteger('queue_number')->nullable()->after('id');
            $table->string('jenis_kelamin', 191)->nullable()->after('nik_pemohon');
            $table->string('pendidikan', 191)->nullable()->after('jenis_kelamin');
            $table->text('saran_kritik')->after('pendidikan');

            // Ubah kolom status jadi varchar
            $table->string('status', 191)->default('diajukan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iumk_submissions', function (Blueprint $table) {
            // Hapus kolom tambahan
            $table->dropColumn(['queue_number', 'jenis_kelamin', 'pendidikan', 'saran_kritik']);

            // Balikin ke enum semula
            $table->enum('status', [
                'diajukan',
                'checked_by_kasi',
                'approved_by_sekcam',
                'approved_by_camat',
                'rejected'
            ])->default('diajukan')->change();
        });
    }
};
