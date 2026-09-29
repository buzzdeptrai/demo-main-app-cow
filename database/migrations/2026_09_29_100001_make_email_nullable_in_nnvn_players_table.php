<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeEmailNullableInNnvnPlayersTable extends Migration
{
    public function up()
    {
        Schema::table('nnvn_players', function (Blueprint $table) {
            $table->string('email', 255)->nullable()->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('nnvn_players', function (Blueprint $table) {
            $table->string('email', 255)->unique()->change();
        });
    }
}
