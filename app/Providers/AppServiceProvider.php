<?php

namespace App\Providers;

use App\Models\Mission;
use RestCord\DiscordClient;
use Laravel\Passport\Passport;
use App\Observers\MissionObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            \App\Models\Mission::class,
        ]);

        Mission::observe(MissionObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();

        $this->app->bind(DiscordClient::class, function ($app) {
            return new DiscordClient([
                'token' => config('services.discord.token'),
                'logger' => new \Psr\Log\NullLogger
            ]);
        });

        require_once app_path('Support/Helpers.php');
    }
}
