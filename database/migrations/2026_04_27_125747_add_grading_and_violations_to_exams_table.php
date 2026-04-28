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
        Schema::table('exams', function (Blueprint $table) {
            $table->integer('pg_weight')->default(70)->after('randomize_answers');
            $table->integer('essay_weight')->default(30)->after('pg_weight');
            $table->integer('max_violations')->default(3)->after('essay_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['pg_weight', 'essay_weight', 'max_violations']);
        });
    }
};
