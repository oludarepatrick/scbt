<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('users', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('firstname');
        $table->string('lastname');
        $table->string('class')->nullable();
        $table->string('email')->unique();
        $table->string('password');            // hashed password
        $table->string('visible_password');    // plain-text copy
        $table->string('category')->default('Student'); // Student, Staff, Admin
        $table->string('phone')->nullable();
        $table->string('term')->nullable();
        $table->string('session')->nullable();
        $table->string('status')->default(1); // 1=Active, 0=Inactive
        $table->integer('is_admin')->default(2); // 1=Staff, 2=Student
        $table->rememberToken();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
