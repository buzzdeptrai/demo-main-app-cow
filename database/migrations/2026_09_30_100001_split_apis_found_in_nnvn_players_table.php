<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SplitApisFoundInNnvnPlayersTable extends Migration
{
    public function up()
    {
        // Add new columns after total_apis_found
        DB::statement('ALTER TABLE nnvn_players ADD COLUMN round_apis_found INT DEFAULT 0 AFTER total_apis_found');
        DB::statement('ALTER TABLE nnvn_players ADD COLUMN gift_apis_found INT DEFAULT 0 AFTER round_apis_found');

        // Copy existing data: total_apis_found → round_apis_found
        DB::statement('UPDATE nnvn_players SET round_apis_found = total_apis_found');

        // Drop old column
        DB::statement('ALTER TABLE nnvn_players DROP COLUMN total_apis_found');
    }

    public function down()
    {
        // Re-add total_apis_found
        DB::statement('ALTER TABLE nnvn_players ADD COLUMN total_apis_found INT DEFAULT 0 AFTER email');

        // Copy data back: round_apis_found + gift_apis_found → total_apis_found
        DB::statement('UPDATE nnvn_players SET total_apis_found = round_apis_found + gift_apis_found');

        // Drop the split columns
        DB::statement('ALTER TABLE nnvn_players DROP COLUMN round_apis_found');
        DB::statement('ALTER TABLE nnvn_players DROP COLUMN gift_apis_found');
    }
}
