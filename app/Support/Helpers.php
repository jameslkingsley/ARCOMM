<?php

if (!function_exists('array_to_object')) {
    /**
     * Converts an array to and object.
     *
     * @return object
     */
    function array_to_object(array $array)
    {
        return json_decode(json_encode($array), false);
    }
}
