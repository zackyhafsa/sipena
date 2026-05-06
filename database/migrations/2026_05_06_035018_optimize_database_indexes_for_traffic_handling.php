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
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('nisn');
            $table->index('classroom_id');
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['nisn']);
            $table->dropIndex(['classroom_id']);
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['exam_id']);
        });
    }
};
