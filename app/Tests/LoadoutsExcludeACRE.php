<?php

namespace App\Tests;

use Illuminate\Support\Facades\Storage;

class LoadoutsExcludeACRE extends MissionTest
{
    /**
     * Determines if the test passes.
     *
     * @return boolean
     */
    public function passes($fail)
    {
        if (!file_exists("{$this->fullUnpacked}/loadouts")) {
            return $fail('Missing loadouts directory');
        }

        $files = Storage::allFiles("{$this->unpacked}/loadouts");

        foreach ($files as $file) {
            $contents = strtolower(Storage::get($file));

            if (str_contains($contents, 'acre_')) {
                $relativeFile = str_replace_first($this->unpacked . '/', '', $file);

                $fail("$relativeFile contains use of ACRE");
            }
        }

        return true;
    }
}
