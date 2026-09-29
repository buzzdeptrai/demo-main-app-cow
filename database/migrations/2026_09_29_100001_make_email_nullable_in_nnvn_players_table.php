<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeEmailNullableInNnvnPlayersTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE nnvn_players MODIFY email VARCHAR(255) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE nnvn_players MODIFY email VARCHAR(255) NOT NULL');
    }
}
