<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Setup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //--- Create Status Table
        Schema::create('join_statuses', function($table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('text');
            $table->string('permalink');
            $table->integer('position')->default(99);
            $table->boolean('protected')->default(false)->unsigned();
        });

        //--- Create Join Request Table
        Schema::create('join_requests', function($table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('name');
            $table->integer('age')->unsigned();
            $table->string('location');
            $table->string('email');
            $table->string('steam');
            $table->boolean('available');
            $table->boolean('apex');
            $table->boolean('groups');
            $table->longtext('experience');
            $table->longtext('bio');
            $table->integer('status_id')->unsigned();
        });

        //--- Add Foreign Keys
        Schema::table('join_requests', function($table) {
            $table->foreign('status_id')->references('id')->on('join_statuses');
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
