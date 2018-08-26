<?php

namespace Tests;

use App\Models\User;
use App\Models\Mission;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase,
        CreatesApplication;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->withoutMiddleware(
            \App\Http\Middleware\VerifyCsrfToken::class
        );

        $this->user = User::create([
            'discord_id' => '376552105571385355',
            'name' => 'Kingsley',
            'email' => 'jlkingsley97@gmail.com',
        ]);

        $this->defaultHeaders['X-CSRF-TOKEN'] = csrf_token();
        $this->defaultHeaders['Authorization'] = 'Bearer ' . $this->user->makeApiToken();
    }

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate:fresh');
        $this->artisan('passport:install');
        $this->artisan('db:seed');

        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Cleans the local mission storage path.
     *
     * @return void
     */
    public function clean()
    {
        File::cleanDirectory(storage_path('app/missions_test'));
    }
}
