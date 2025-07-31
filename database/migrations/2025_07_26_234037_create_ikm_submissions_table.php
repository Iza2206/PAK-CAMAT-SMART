<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ikm_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->float('nilai'); // nilai IKM
            $table->enum('status', ['terkirim', 'draft'])->default('draft');
            $table->timestamp('submitted_at');
            $table->integer('duration_seconds')->default(0); // lama waktu isi dalam detik
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ikm_submissions');
    }
};
