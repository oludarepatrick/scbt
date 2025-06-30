<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained()->onDelete('cascade');
            $table->string('class');
            $table->string('subject');
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->string('option_e')->nullable();
            $table->char('correct_option', 1);
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
        Schema::dropIfExists('ai_questions');
    }
}
