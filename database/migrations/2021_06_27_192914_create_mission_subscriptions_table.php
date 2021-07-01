<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('discord_id')->nullable()->unique()->change();
        });

        Schema::create('mission_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('mission_id')->unsigned();
            $table->string('discord_id')->nullable();
            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade');
            $table->foreign('discord_id')->references('discord_id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('mission_subscriptions');
    }
}
