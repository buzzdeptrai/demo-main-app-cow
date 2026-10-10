<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeNnvnForLoad extends Migration
{
    public function up()
    {
        // Index for game <-> player lookups (joins, player game history)
        Schema::table('nnvn_games', function (Blueprint $table) {
            $table->index('player_id', 'nnvn_games_player_id_index');
        });

        // Stored generated column: makes leaderboard sort + rank count index-backed,
        // removing the full-table sort on the computed (round_apis_found + gift_apis_found).
        DB::statement('ALTER TABLE nnvn_players ADD COLUMN total_apis INT AS (round_apis_found + gift_apis_found) STORED');
        DB::statement('CREATE INDEX nnvn_players_rank_index ON nnvn_players (total_apis, best_total_time)');
    }

    public function down()
    {
        DB::statement('DROP INDEX nnvn_players_rank_index ON nnvn_players');
        DB::statement('ALTER TABLE nnvn_players DROP COLUMN total_apis');

        Schema::table('nnvn_games', function (Blueprint $table) {
            $table->dropIndex('nnvn_games_player_id_index');
        });
    }
}
