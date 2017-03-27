<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfigJsonFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function($table) {
            $table->longText('sqm_json')->nullable();
            $table->longText('ext_json')->nullable();
            $table->longText('cfg_json')->nullable();
            $table->string('version')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('missions', function($table) {
            $table->dropColumn('sqm_json');
            $table->dropColumn('ext_json');
            $table->dropColumn('cfg_json');
            $table->dropColumn('version');
        });
    }
}
