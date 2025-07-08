<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeLeftColumnToCurriculumsTa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curriculums_ta', function (Blueprint $table) {
            $table->string('time_left')->nullable()->after('class');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curriculums_ta', function (Blueprint $table) {
            $table->dropColumn('time_left');
        });
    }
}
