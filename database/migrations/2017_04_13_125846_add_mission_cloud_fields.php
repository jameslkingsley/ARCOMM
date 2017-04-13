<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissionCloudFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function($table) {
            $table->string('cloud_pbo')->nullable();
            $table->string('cloud_zip')->nullable();
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
            $table->dropColumn('cloud_pbo');
            $table->dropColumn('cloud_zip');
        });
    }
}
