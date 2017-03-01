<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missions', function($table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('last_played')->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('file_name');
            $table->string('display_name');
            $table->enum('mode', ['coop', 'adversarial', 'preop'])->default('coop');
            $table->longText('summary');
            $table->integer('map_id')->unsigned();
            $table->string('pbo_path');
            $table->boolean('published')->default(false);
            $table->boolean('locked_blufor_briefing')->default(false);
            $table->boolean('locked_opfor_briefing')->default(false);
            $table->boolean('locked_indfor_briefing')->default(false);
            $table->boolean('locked_civilian_briefing')->default(false);
            $table->boolean('locked_gamemaster_briefing')->default(false);
        });

        Schema::table('missions', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('map_id')->references('id')->on('maps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('missions');
    }
}
