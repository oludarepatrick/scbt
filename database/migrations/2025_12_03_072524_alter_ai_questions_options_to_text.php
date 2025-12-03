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
        Schema::table('ai_questions', function (Blueprint $table) {
            $table->text('option_a')->change();
            $table->text('option_b')->change();
            $table->text('option_c')->nullable()->change();
            $table->text('option_d')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_questions', function (Blueprint $table) {
            $table->string('option_a', 255)->change();
            $table->string('option_b', 255)->change();
            $table->string('option_c', 255)->nullable()->change();
            $table->string('option_d', 255)->nullable()->change();
        });
    }
};
