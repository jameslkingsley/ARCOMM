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
     * Stack of errors and data from previous tests.
     *
     * @var object
     */
    protected $stack;

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct($request, $stack)
    {
        $this->request = $request;
        $this->stack = $stack;
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
