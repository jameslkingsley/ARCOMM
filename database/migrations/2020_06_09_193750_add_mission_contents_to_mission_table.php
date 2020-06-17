<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissionContentsToMissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function($table) {
            $table->longText('briefings')->nullable();
            $table->longText('dependencies')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->longText('weather')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
