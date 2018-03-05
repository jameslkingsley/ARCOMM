<?php

namespace App\Tests;

class LoadoutsExcludeACRE extends MissionTest
{
    /**
     * Is the test strict?
     * Strict tests prevent continuation.
     *
     * @var boolean
     */
    protected $strict = false;

    /**
     * Determines if the test passes.
     *
     * @return boolean
     */
    public function passes($fail)
    {
        // TODO Check for ACRE in loadouts

        return true;
    }
}
