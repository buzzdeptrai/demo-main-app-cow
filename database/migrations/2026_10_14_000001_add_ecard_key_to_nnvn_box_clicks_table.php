<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEcardKeyToNnvnBoxClicksTable extends Migration
{
    public function up()
    {
        Schema::table('nnvn_box_clicks', function (Blueprint $table) {
            $table->string('ecard_key', 50)->nullable()->after('message_text');
        });
    }

    public function down()
    {
        Schema::table('nnvn_box_clicks', function (Blueprint $table) {
            $table->dropColumn('ecard_key');
        });
    }
}
