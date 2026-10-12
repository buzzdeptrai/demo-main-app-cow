<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTotalPlayTimeToNnvnPlayersTable extends Migration
{
    public function up()
    {
        Schema::table('nnvn_players', function (Blueprint $table) {
            $table->decimal('total_play_time', 10, 1)->default(0)->after('best_total_time');
        });

        // Backfill cumulative play time from completed games.
        DB::statement('
            UPDATE nnvn_players p
            SET total_play_time = (
                SELECT COALESCE(SUM(g.total_time), 0)
                FROM nnvn_games g
                WHERE g.player_id = p.id AND g.status = "completed"
            )
        ');

        // Rank is total_apis DESC, then total_play_time ASC (tie-break).
        DB::statement('DROP INDEX nnvn_players_rank_index ON nnvn_players');
        DB::statement('CREATE INDEX nnvn_players_rank_index ON nnvn_players (total_apis, total_play_time)');
    }

    public function down()
    {
        DB::statement('DROP INDEX nnvn_players_rank_index ON nnvn_players');
        DB::statement('CREATE INDEX nnvn_players_rank_index ON nnvn_players (total_apis, best_total_time)');

        Schema::table('nnvn_players', function (Blueprint $table) {
            $table->dropColumn('total_play_time');
        });
    }
}
