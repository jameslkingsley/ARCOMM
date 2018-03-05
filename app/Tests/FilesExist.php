<?php

namespace App\Tests;

class FilesExist extends MissionTest
{
    /**
     * Required mission files.
     *
     * @var array
     */
    protected $files = [
        'config.hpp',
        'version.txt',
        'mission.sqm',
        'description.ext',
    ];

    /**
     * Determines if the test passes.
     *
     * @return boolean
     */
    public function passes($fail)
    {
        foreach ($this->files as $file) {
            if (!file_exists("{$this->unpacked}/$file")) {
                return $fail("Missing $file");
            }
        }

        return true;
    }
}
