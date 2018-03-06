<?php

namespace App\Parser;

class Common
{
    /**
     * Token types.
     *
     * @var array
     */
    public static $types = [
        'class' => 0,
        'value' => 1,
        'array' => 2,
        'extern' => 3,
        'delete' => 4,
    ];

    /**
     * Token sub-types.
     *
     * @var array
     */
    public static $subTypes = [
        'string' => 0,
        'float' => 1,
        'long' => 2,
        'array' => 3,
        'string_var' => 4,
    ];
}
