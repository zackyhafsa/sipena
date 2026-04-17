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
        Schema::table('questions', function (Blueprint $table) {
            $table->enum('type', ['multiple_choice', 'essay'])->default('multiple_choice')->after('question_text');
            $table->text('correct_answer_essay')->nullable()->after('correct_answer');
            $table->string('correct_answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'correct_answer_essay']);
            $table->string('correct_answer')->nullable(false)->change();
        });
    }
};
