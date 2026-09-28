<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionsEnToNnvnQuizQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('nnvn_quiz_questions', function (Blueprint $table) {
            $table->json('options_en')->nullable()->after('options');
        });
    }

    public function down()
    {
        Schema::table('nnvn_quiz_questions', function (Blueprint $table) {
            $table->dropColumn('options_en');
        });
    }
}
