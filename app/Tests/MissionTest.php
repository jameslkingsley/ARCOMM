<?php

namespace App\Tests;

abstract class MissionTest
{
    /**
     * Is the test strict?
     * Strict tests prevent continuation.
     *
     * @var boolean
     */
    protected $strict = true;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Determines if the test is strict.
     *
     * @return boolean
     */
    public function isStrict()
    {
        return (bool) $this->strict;
    }

    /**
     * Proxy for the request object.
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->request->{$name};
    }
}
