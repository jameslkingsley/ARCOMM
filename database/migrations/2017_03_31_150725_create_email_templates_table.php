<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('subject');
            $table->longText('content');
            $table->boolean('locked')->default(false);
        });

        DB::table('email_templates')->insert([
            'subject' => 'Thank you for your application!',
            'content' => '',
            'locked' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
}
