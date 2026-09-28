<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNnvnPlayersTable extends Migration
{
    public function up()
    {
        Schema::create('nnvn_players', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 255)->unique();
            $table->integer('total_apis_found')->default(0);
            $table->integer('total_sessions')->default(0);
            $table->decimal('best_total_time', 6, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nnvn_players');
    }
}
