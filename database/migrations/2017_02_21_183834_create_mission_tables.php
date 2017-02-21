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
            $table->integer('revisions')->default(0);
            $table->boolean('published')->default(false);
        });

        Schema::table('missions', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('map_id')->references('id')->on('maps');
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
