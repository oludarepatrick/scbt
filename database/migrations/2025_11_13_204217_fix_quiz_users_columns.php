<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_users', function (Blueprint $table) {
            $table->bigInteger('time_left')->nullable()->change();
            $table->tinyInteger('status')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('quiz_users', function (Blueprint $table) {
            $table->text('time_left')->nullable()->change();
            $table->string('status')->default('1')->change();
        });
    }
};
