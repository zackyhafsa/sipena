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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // ID Siswa
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->integer('score')->nullable();
            $table->json('answers_log')->nullable(); // Menyimpan jawaban siswa
            $table->integer('cheat_warning_count')->default(0); // Menghitung berapa kali pindah tab
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
