<?php

namespace Tests;

use App\Models\User;
use App\Models\Mission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Dumps the contents of the response.
     *
     * @return void
     */
    public function dump(TestResponse $response)
    {
        dd($response->decodeResponseJson());
    }

    /**
     * Authenticates with a user.
     *
     * @return void
     */
    public function login($user = null)
    {
        auth()->login($user ?: User::first());
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

    /**
     * Uploads the given mission file.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function uploadMission($name)
    {
        $response = $this->post('/api/mission', [
            '_token' => csrf_token(),
            'file' => new UploadedFile(
                base_path("tests/$name"),
                $name,
                null,
                null,
                null,
                true
            )
        ]);

        optional(Mission::orderBy('created_at', 'desc')->first())->delete();

        return $response;
    }
}
