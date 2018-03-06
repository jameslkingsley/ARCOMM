<?php

namespace App\Tests;

use App\Support\ArmaConfig;

class ValidSyntax extends MissionTest
{
    /**
     * Determines if the test passes.
     *
     * @return boolean
     */
    public function passes($fail)
    {
        try {
            $this->decodeDescription();
            // $this->decodeConfig();
            // $this->decodeMission();

            return true;
        } catch (\Exception $error) {
            throw $error;
            // return $fail($error->getMessage());
        }
    }

    /**
     * Decodes the description.ext file.
     *
     * @return void
     */
    public function decodeDescription()
    {
        $contents = file_get_contents("{$this->fullUnpacked}/description.ext");

        $data = new ArmaConfig($contents);

        info($data->toArray());
    }

    /**
     * Decodes the config.hpp file.
     *
     * @return void
     */
    public function decodeConfig()
    {
        $contents = file_get_contents("{$this->fullUnpacked}/config.hpp");

        new ArmaConfig($contents);
    }

    /**
     * Decodes the mission.sqm file.
     *
     * @return void
     */
    public function decodeMission()
    {
        $contents = file_get_contents("{$this->fullUnpacked}/mission.sqm");

        $config = (new ArmaConfig($contents))->toJson();

        info($config);
    }
}
