<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBoxTypeToNnvnBoxClicksTable extends Migration
{
    public function up()
    {
        Schema::table('nnvn_box_clicks', function (Blueprint $table) {
            $table->string('box_type', 20)->default('link')->after('box_index');
        });
    }

    public function down()
    {
        Schema::table('nnvn_box_clicks', function (Blueprint $table) {
            $table->dropColumn('box_type');
        });
    }
}
