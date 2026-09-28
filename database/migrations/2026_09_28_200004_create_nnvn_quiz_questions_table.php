<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNnvnQuizQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('nnvn_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_vi');
            $table->text('question_en');
            $table->json('options');
            $table->tinyInteger('correct_index');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nnvn_quiz_questions');
    }
}
