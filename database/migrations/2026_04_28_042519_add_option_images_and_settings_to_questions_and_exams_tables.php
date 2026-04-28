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
            $table->string('option_a_image')->nullable()->after('option_a');
            $table->string('option_b_image')->nullable()->after('option_b');
            $table->string('option_c_image')->nullable()->after('option_c');
            $table->string('option_d_image')->nullable()->after('option_d');
            $table->string('option_e_image')->nullable()->after('option_e');

            // Ubah tipe kolom text menjadi nullable agar opsi gambar saja diperbolehkan
            $table->string('option_a')->nullable()->change();
            $table->string('option_b')->nullable()->change();
            $table->string('option_c')->nullable()->change();
            $table->string('option_d')->nullable()->change();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('show_result_on_completion')->default(true)->after('max_violations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'option_a_image',
                'option_b_image',
                'option_c_image',
                'option_d_image',
                'option_e_image',
            ]);

            $table->string('option_a')->nullable(false)->change();
            $table->string('option_b')->nullable(false)->change();
            $table->string('option_c')->nullable(false)->change();
            $table->string('option_d')->nullable(false)->change();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('show_result_on_completion');
        });
    }
};
