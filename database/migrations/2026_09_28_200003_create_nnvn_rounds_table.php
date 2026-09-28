<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNnvnRoundsTable extends Migration
{
    public function up()
    {
        Schema::create('nnvn_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('nnvn_games')->onDelete('cascade');
            $table->tinyInteger('round_number');
            $table->decimal('time_seconds', 5, 1);
            $table->boolean('quiz_used')->default(false);
            $table->timestamp('created_at')->nullable();

            $table->unique(['game_id', 'round_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('nnvn_rounds');
    }
}
