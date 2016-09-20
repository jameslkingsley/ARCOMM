<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('join_statuses')->insert([
            'text' => 'Pending',
            'permalink' => 'pending',
            'position' => 0,
            'protected' => true
        ]);
        
        DB::table('join_statuses')->insert([
            'text' => 'Approved',
            'permalink' => 'approved',
            'position' => 1,
            'protected' => true
        ]);
        
        DB::table('join_statuses')->insert([
            'text' => 'Declined',
            'permalink' => 'declined',
            'position' => 2,
            'protected' => true
        ]);
        
        DB::table('join_statuses')->insert([
            'text' => 'Blacklisted',
            'permalink' => 'blacklisted',
            'position' => 3,
            'protected' => true
        ]);
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
