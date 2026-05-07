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
        // Users: Composite index for school and role filtering
        Schema::table('users', function (Blueprint $table) {
            $table->index(['school_id', 'role']);
        });

        // Exam Results: Composite index for quick lookups
        Schema::table('exam_results', function (Blueprint $table) {
            $table->index(['user_id', 'exam_id'], 'user_exam_unique_check');
        });

        // Exam-School Pivot
        Schema::table('exam_school', function (Blueprint $table) {
            $table->index(['exam_id', 'school_id']);
        });

        // Questions: Questions are often fetched by exam_id
        Schema::table('questions', function (Blueprint $table) {
            $table->index(['exam_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['school_id', 'role']);
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropIndex('user_exam_unique_check');
        });

        Schema::table('exam_school', function (Blueprint $table) {
            $table->dropIndex(['exam_id', 'school_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['exam_id', 'type']);
        });
    }
};
