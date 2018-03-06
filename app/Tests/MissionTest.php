<?php

namespace App\Tests;

abstract class MissionTest
{
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
     * Proxy for the request object.
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->request->{$name};
    }
}
