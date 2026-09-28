<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNnvnBoxClicksTable extends Migration
{
    public function up()
    {
        Schema::create('nnvn_box_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('nnvn_games')->onDelete('cascade');
            $table->unsignedTinyInteger('round_number');
            $table->unsignedTinyInteger('box_index');
            $table->boolean('is_apis')->default(false);
            $table->boolean('is_correct')->default(false);
            $table->string('url_opened')->nullable();
            $table->timestamp('clicked_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nnvn_box_clicks');
    }
}
