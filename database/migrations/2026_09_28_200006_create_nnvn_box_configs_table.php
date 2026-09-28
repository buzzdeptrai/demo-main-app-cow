<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNnvnBoxConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('nnvn_box_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('box_index')->unique();
            $table->string('url');
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nnvn_box_configs');
    }
}
