<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNnvnGamesTable extends Migration
{
    public function up()
    {
        Schema::create('nnvn_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('nnvn_players')->onDelete('cascade');
            $table->enum('status', ['playing', 'completed', 'abandoned'])->default('playing');
            $table->decimal('total_time', 6, 1)->nullable();
            $table->integer('quiz_count')->default(0);
            $table->integer('apis_found')->default(0);
            $table->boolean('gift_apis_found')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('completed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nnvn_games');
    }
}
