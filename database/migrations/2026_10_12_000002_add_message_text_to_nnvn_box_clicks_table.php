<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessageTextToNnvnBoxClicksTable extends Migration
{
    public function up()
    {
        Schema::table('nnvn_box_clicks', function (Blueprint $table) {
            $table->string('message_text')->nullable()->after('url_opened');
        });
    }

    public function down()
    {
        Schema::table('nnvn_box_clicks', function (Blueprint $table) {
            $table->dropColumn('message_text');
        });
    }
}
