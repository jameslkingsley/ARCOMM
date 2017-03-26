<?php

namespace App\Helpers;

class ArmaConfigParserError
{
    /**
     * Message string.
     *
     * @var string
     */
    public $message;

    /**
     * Constructor method.
     *
     * @return any
     */
    public function __construct($message = '')
    {
        $this->message = $message;
        return $this;
    }
}
