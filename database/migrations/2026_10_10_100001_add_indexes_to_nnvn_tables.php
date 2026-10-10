<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToNnvnTables extends Migration
{
    public function up()
    {
        Schema::table('nnvn_players', function (Blueprint $table) {
            $table->index('best_total_time', 'nnvn_players_best_total_time_index');
        });

        Schema::table('nnvn_games', function (Blueprint $table) {
            $table->index(['status', 'gift_apis_found'], 'nnvn_games_status_gift_index');
            $table->index('completed_at', 'nnvn_games_completed_at_index');
        });

        Schema::table('nnvn_quiz_questions', function (Blueprint $table) {
            $table->index('is_active', 'nnvn_quiz_questions_is_active_index');
        });
    }

    public function down()
    {
        Schema::table('nnvn_players', function (Blueprint $table) {
            $table->dropIndex('nnvn_players_best_total_time_index');
        });

        Schema::table('nnvn_games', function (Blueprint $table) {
            $table->dropIndex('nnvn_games_status_gift_index');
            $table->dropIndex('nnvn_games_completed_at_index');
        });

        Schema::table('nnvn_quiz_questions', function (Blueprint $table) {
            $table->dropIndex('nnvn_quiz_questions_is_active_index');
        });
    }
}
