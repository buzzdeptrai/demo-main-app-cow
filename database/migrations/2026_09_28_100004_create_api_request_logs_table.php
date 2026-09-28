<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mini_app_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('token_id')->nullable();
            $table->string('method', 10);
            $table->string('path', 500);
            $table->smallInteger('status_code');
            $table->string('ip_address', 45);
            $table->integer('response_time_ms');
            $table->timestamp('created_at')->nullable();

            $table->index(['mini_app_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_request_logs');
    }
}
