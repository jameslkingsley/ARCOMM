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
    public function passes($fail, $data)
    {
        try {
            $data([
                'ext' => $this->decodeDescription(),
                'cfg' => $this->decodeConfig(),
                'sqm' => $this->decodeMission(),
            ]);

            return true;
        } catch (\Exception $error) {
            return $fail($error->getMessage());
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

        return ArmaConfig::parse($contents);
    }

    /**
     * Decodes the config.hpp file.
     *
     * @return void
     */
    public function decodeConfig()
    {
        $contents = file_get_contents("{$this->fullUnpacked}/config.hpp");

        return ArmaConfig::parse($contents);
    }

    /**
     * Decodes the mission.sqm file.
     *
     * @return void
     */
    public function decodeMission()
    {
        $contents = file_get_contents("{$this->fullUnpacked}/mission.sqm");

        return ArmaConfig::parse($contents);
    }
}
