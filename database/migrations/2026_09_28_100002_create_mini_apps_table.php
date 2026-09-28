<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiniAppsTable extends Migration
{
    public function up()
    {
        Schema::create('mini_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->string('version', 20)->default('1.0.0');
            $table->integer('api_rate_limit')->default(60);
            $table->string('webhook_url', 500)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mini_apps');
    }
}
