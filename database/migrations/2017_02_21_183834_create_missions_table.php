<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref')->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('map_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('map_id')->references('id')->on('maps')->onDelete('cascade');
            $table->string('name');
            $table->longText('summary');
            $table->enum('mode', ['coop', 'adversarial', 'preop'])->default('coop');
            $table->unsignedInteger('verified_by')->nullable();
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('last_played')->nullable();
            $table->json('locked_briefings')->nullable();
            $table->longText('ext')->nullable();
            $table->longText('cfg')->nullable();
            $table->longText('sqm')->nullable();
            $table->timestamps();
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
