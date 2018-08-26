<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mission;
use Illuminate\Http\UploadedFile;

class MissionUploadTest extends TestCase
{
    /**
     * Uploads the given mission file.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function uploadMission($name)
    {
        // $this->withExceptionHandling();

        $response = $this
            ->actingAs($this->user, 'api')
            ->post('/api/mission', [
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

    /** @test */
    public function can_upload_valid_mission_file()
    {
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_BibungBay_K.pulau.pbo')
            ->assertStatus(201);
    }

    /** @test */
    public function cant_upload_mission_file_with_missing_files()
    {
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_MissingFiles_K.Malden.pbo')
            ->assertStatus(500);
    }

    /** @test */
    public function cant_upload_mission_file_with_missing_mission_intel()
    {
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_MissingIntel_K.Malden.pbo')
            ->assertStatus(500);
    }

    /** @test */
    public function can_upload_mission_file_with_acre_in_loadouts_but_errors_returned()
    {
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_LoadoutsContainACRE_K.Malden.pbo')
            ->assertStatus(201);
    }

    /** @test */
    public function cant_upload_mission_file_with_syntax_errors()
    {
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_SyntaxError_K.Malden.pbo')
            ->assertStatus(500);
    }
}
