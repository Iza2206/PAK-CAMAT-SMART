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
        Schema::create('dokumen_layanan', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('layanan_id');
        $table->string('nama_file');
        $table->string('path'); // path file di storage/app/public
        $table->enum('uploaded_by', ['meja_layanan', 'kasi_kesos', 'sekcam', 'camat']);
        $table->timestamps();

        $table->foreign('layanan_id')->references('id')->on('layanan')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_layanan');
    }
};
