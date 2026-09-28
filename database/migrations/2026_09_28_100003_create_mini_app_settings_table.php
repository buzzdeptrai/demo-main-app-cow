<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiniAppSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('mini_app_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mini_app_id')->constrained()->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->enum('type', ['string', 'integer', 'boolean', 'json'])->default('string');
            $table->timestamps();
            $table->unique(['mini_app_id', 'key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mini_app_settings');
    }
}
