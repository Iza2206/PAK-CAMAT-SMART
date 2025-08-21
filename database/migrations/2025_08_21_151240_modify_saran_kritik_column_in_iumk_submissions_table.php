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
            // Mengubah kolom 'saran_kritik' menjadi TEXT dengan charset utf8mb4 dan collation utf8mb4_unicode_ci
            $table->text('saran_kritik')
                  ->charset('utf8mb4')
                  ->collation('utf8mb4_unicode_ci')
                  ->nullable() // Agar bisa kosong (nullable), bisa sesuaikan dengan kebutuhan
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('iumk_submissions', function (Blueprint $table) {
            // Mengembalikan perubahan, bisa disesuaikan jika ingin mengembalikan tipe atau kolom lainnya
            $table->text('saran_kritik')->nullable(false)->change();
        });
    }
};
