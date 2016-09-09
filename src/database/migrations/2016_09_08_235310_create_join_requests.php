<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoinRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('join_requests', function($table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->string('name');
            $table->integer('age');
            $table->string('location');
            $table->string('email');
            $table->string('steam');
            $table->boolean('available');
            $table->boolean('apex');
            $table->boolean('groups');
            $table->longtext('experience');
            $table->longtext('bio');
            $table->tinyInteger('status')->default(0);
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
