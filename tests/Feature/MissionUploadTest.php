<?php

namespace Tests\Feature;

use Tests\TestCase;

class MissionUploadTest extends TestCase
{
    /** @test */
    public function can_upload_valid_mission_file()
    {
        $this->login();
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_Valid_K.Malden.pbo')
            ->assertStatus(200);
    }

    /** @test */
    public function cant_upload_mission_file_with_missing_files()
    {
        $this->login();
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_MissingFiles_K.Malden.pbo')
            ->assertStatus(500);
    }

    /** @test */
    public function can_upload_mission_file_with_acre_in_loadouts_but_errors_returned()
    {
        $this->login();
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_LoadoutsContainACRE_K.Malden.pbo')
            ->assertStatus(200);
    }

    /** @test */
    public function cant_upload_mission_file_with_syntax_errors()
    {
        $this->login();
        $this->clean();

        $response = $this->uploadMission('ARC_COOP_SyntaxError_K.Malden.pbo')
            ->assertStatus(500);
    }
}
