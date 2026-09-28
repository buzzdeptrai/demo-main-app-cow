<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNnvnQuizAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('nnvn_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('nnvn_games')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('nnvn_quiz_questions')->onDelete('cascade');
            $table->integer('answer_index');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nnvn_quiz_answers');
    }
}
