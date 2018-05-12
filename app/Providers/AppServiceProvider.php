<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Missions\Mission;
use App\Observers\MissionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Mission::observe(MissionObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Support/Helpers.php');
    }
}
