<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Kingsley',
            'steam_id' => '76561198115517788',
            'avatar' => 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/98/986dd21dce6f9ce3354d8de40626ea9f1693a3ad_full.jpg',
        ]);
    }
}
