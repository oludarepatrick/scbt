<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsNullableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
                $table->string('othername')->nullable()->change();
                $table->string('state')->nullable()->change();
                $table->string('address')->nullable()->change();
                $table->string('state_of_origin')->nullable()->change();
                $table->string('nationality')->nullable()->change();
                $table->string('religion')->nullable()->change();
                $table->string('city')->nullable()->change();
                $table->string('house')->nullable()->change();
                $table->string('blood_grp')->nullable()->change();
                $table->string('photo')->nullable()->change();
                $table->string('thumb_url')->nullable()->change();
                $table->string('image_url')->nullable()->change();
                $table->string('status')->nullable()->change();
                $table->string('tag')->nullable()->change();
                $table->string('date_of_tag')->nullable()->change();
                $table->string('date_admitted')->nullable()->change();
                $table->string('last_school')->nullable()->change();
                $table->string('last_class')->nullable()->change();
                $table->string('genotype')->nullable()->change();
                $table->string('parent_name')->nullable()->change();
                $table->string('occupation')->nullable()->change();
                $table->string('updated_email')->nullable()->change();
                $table->string('date_of_tag')->nullable()->change();
                $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            
                $table->string('othername')->nullable()->change();
                $table->string('state')->nullable()->change();
                $table->string('address')->nullable()->change();
                $table->string('state_of_origin')->nullable()->change();
                $table->string('nationality')->nullable()->change();
                $table->string('religion')->nullable()->change();
                $table->string('city')->nullable()->change();
                $table->string('house')->nullable()->change();
                $table->string('blood_grp')->nullable()->change();
                $table->string('photo')->nullable()->change();
                $table->string('thumb_url')->nullable()->change();
                $table->string('image_url')->nullable()->change();
                $table->string('status')->nullable()->change();
                $table->string('tag')->nullable()->change();
                $table->string('date_of_tag')->nullable()->change();
                $table->string('date_admitted')->nullable()->change();
                $table->string('last_school')->nullable()->change();
                $table->string('last_class')->nullable()->change();
                $table->string('genotype')->nullable()->change();
                $table->string('parent_name')->nullable()->change();
                $table->string('occupation')->nullable()->change();
                $table->string('updated_email')->nullable()->change();
                $table->string('date_of_tag')->nullable()->change();
                $table->dropTimestamps(); // Removes created_at and updated_at if rolled back
        });
    }
}
